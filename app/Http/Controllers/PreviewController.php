<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessConversion;
use App\Models\Conversion;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Parsedown;

class PreviewController extends Controller
{
    const STORAGE_DIR = "conversions";

    /**
     * @param Request $request
     * @param $random
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function dispatchJob(Request $request, string $random)
    {
        /** @var File $storedFile */
        $storedFile = File::where('random', $random)->first();
        abort_if(empty($storedFile), 404);

        $extraPath = $request->input('path');
        $download = $request->input('type') === 'download';

        if ($conversion = $storedFile->conversion) {
            switch ($conversion->status) {
                case Conversion::SUCCESS:
                    $sha512 = $storedFile->sha512;
                    $fullPath = Storage::path(static::hashToPath($sha512));
                    return $this->listDir(
                        $fullPath,
                        $sha512,
                        $storedFile->filename,
                        $extraPath,
                        $download
                    );

                case Conversion::FAIL;
                    return view('preview.fail');

                case Conversion::IN_QUEUE;
                case Conversion::PROCESSING;
                    break;
            }
        } else {
            /** @var Conversion $conversion */
            $conversion = $storedFile->conversion()->create([
                'status' => Conversion::IN_QUEUE,
            ]);

            ProcessConversion::dispatch(
                $conversion,
                Storage::path(
                    File::hashToPath($storedFile->sha512) . DIRECTORY_SEPARATOR . $storedFile->sha512
                ),
                $storedFile->filename
            );
        }

        return view('preview.wait');
    }

    protected function listDir($dirFullPath, $sha512, $shownName, $extraPath = null, $download = false)
    {
        foreach (scandir($dirFullPath) as $item) {
            $pathinfo = pathinfo($item);
            if ($pathinfo['filename'] === $sha512) {
                $filePath = $pathinfo['basename'];
                //if $extraPath
                if ($extraPath !== null) {
                    $filePath .= DIRECTORY_SEPARATOR . $extraPath;
                    if (!file_exists($dirFullPath . DIRECTORY_SEPARATOR . $filePath)) {
                        return view('preview.fail');
                    }
                }

                $absolutePath = $dirFullPath . DIRECTORY_SEPARATOR . $filePath;

                if (is_file($absolutePath)) {
                    if ($download) {
                        return response()->download($absolutePath);
                    }

                    $pathinfo = pathinfo($absolutePath);
                    $extension = strtolower($pathinfo['extension']);
                    switch ($extension) {
                        case 'md':
                            $parsedown = new Parsedown();
                            $content = file_get_contents($absolutePath);
                            return $parsedown->text($content);
                        case 'txt':
                            $content = file_get_contents($absolutePath);
                            $encoding = mb_detect_encoding($content, ['GB2312', 'GBK', 'UTF-16', 'UCS-2', 'UTF-8', 'BIG5', 'ASCII']);
                            return mb_convert_encoding($content, 'UTF-8', $encoding);
                        case 'pdf':
                        case 'jpg':
                        case 'jpeg':
                        case 'bmp':
                        case 'png':
                            return response()->file($absolutePath);
                        default:
                            $sha512 = hash_file("sha512", $absolutePath);
                            $user = Auth::user();
                            if (!$storedFile = $user->files()->where('sha512', $sha512)->first()) {
                                $targetDir = File::hashToPath($sha512);
                                if (!Storage::exists($targetDir)) {
                                    Storage::makeDirectory($targetDir);
                                }

                                $destPath = Storage::path($targetDir . DIRECTORY_SEPARATOR . $sha512);

                                copy($absolutePath, $destPath);
                                $storedFile = $user->files()->create([
                                    'random' => Str::random(80),
                                    'sha512' => $sha512,
                                    'size' => filesize($absolutePath),
                                    'filename' => $pathinfo['basename'],
                                ]);
                            }

                            return redirect("/preview/{$storedFile->random}");
                    }

                } else if (is_dir($absolutePath)) {
                    $files = array_map(function ($basename) use ($absolutePath, $extraPath) {
                        $path = $basename;
                        if ($extraPath !== null) {
                            $path = $extraPath . DIRECTORY_SEPARATOR . $path;
                        }

                        return [
                            'fileName' => $basename . (is_dir($absolutePath . DIRECTORY_SEPARATOR . $basename) ? '/' : ''),
                            'url' => url()->current() . '?' . http_build_query([
                                    'path' => $path, 'type' => 'download']),
                            'preview_url' => url()->current() . '?' . http_build_query([
                                    'path' => $path,
                                ]),
                        ];
                    }, array_merge(array_filter(scandir($absolutePath), function ($basename) {
                        return $basename !== "." && $basename !== "..";
                    })));

                    return view('preview.list', [
                        'basename' => $shownName,
                        'files' => $files,
                    ]);
                }
            }
        }
        return view('preview.fail');
    }

    /**
     * @param Request $request
     * @param $random
     * @return array
     */
    public function queryStatus(Request $request, $random)
    {
        $storedFile = File::where('random', $random)->first();
        abort_if(empty($storedFile), 404);

        if ($conversion = $storedFile->conversion) {
            switch ($conversion->status) {
                case Conversion::SUCCESS:
                    return [
                        'code' => 1,
                        'msg' => '转换成功',
                    ];

                case Conversion::FAIL;
                    return [
                        'code' => -1,
                        'msg' => "转换失败，请手动下载",
                    ];

                case Conversion::IN_QUEUE;
                case Conversion::PROCESSING;
                default:
                    $seconds = Carbon::now()->diffInSeconds($conversion->created_at);
                    return [
                        'code' => 0,
                        'msg' => "转换中，已耗时{$seconds}秒，请等待...",
                    ];
            }
        }

        return [
            'code' => -1,
            'msg' => '请先预览此文件',
        ];
    }

    public static function hashToPath(string $sha512)
    {
        return static::STORAGE_DIR
            . DIRECTORY_SEPARATOR . substr($sha512, 0, 2)
            . DIRECTORY_SEPARATOR . substr($sha512, 2, 2);
    }
}