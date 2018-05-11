<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessConversion;
use App\Models\Conversion;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PreviewController extends Controller
{
    const STORAGE_DIR = "conversions";

    public function __construct()
    {
        $this->middleware('auth');
    }

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

        if (($extraPath = base64_decode($request->input('path'))) === false) {
            return view('preview.fail');
        }

        $download = $request->input('type') === 'download';
        if ($conversion = $storedFile->conversion) {
            switch ($conversion->status) {
                case Conversion::SUCCESS:
                    $random = $storedFile->random;
                    $fullPath = Storage::path(static::strToPath($random));
                    return $this->listDir(
                        $fullPath,
                        $random,
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
            )->delay(now()->addSecond());
        }

        return view('preview.wait');
    }

    public static function strToPath(string $random)
    {
        $random = strtolower($random);

        return static::STORAGE_DIR
            . DIRECTORY_SEPARATOR . substr($random, 0, 2)
            . DIRECTORY_SEPARATOR . substr($random, 2, 2);
    }

    protected function listDir($dirFullPath, $random, $shownName, $extraPath = '', $download = false)
    {
        foreach (scandir($dirFullPath) as $item) {
            $pathinfo = pathinfo($item);
            if ($pathinfo['filename'] === $random) {
                $filePath = $pathinfo['basename'];
                //if $extraPath
                if ($extraPath) {
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
                        case 'html':
                        case 'pdf':
                        case 'jpg':
                        case 'jpeg':
                        case 'bmp':
                        case 'png':
                            return response()->file($absolutePath);

                        default:
                            $sha512 = hash_file("sha512", $absolutePath);

                            if (!$storedFile = File::where([
                                ['sha512', $sha512],
                                ['filename', $pathinfo['basename']],
                            ])->first()) {
                                $targetDir = File::hashToPath($sha512);
                                if (!Storage::exists($targetDir)) {
                                    Storage::makeDirectory($targetDir);
                                }

                                $destPath = Storage::path($targetDir . DIRECTORY_SEPARATOR . $sha512);

                                copy($absolutePath, $destPath);
                                $storedFile = Auth::user()->files()->create([
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
                        if ($extraPath) {
                            $path = $extraPath . DIRECTORY_SEPARATOR . $path;
                        }
                        $base64Path = base64_encode($path);
                        return [
                            'fileName' => $basename . (is_dir($absolutePath . DIRECTORY_SEPARATOR . $basename) ? '/' : ''),
                            'url' => url()->current() . '?'
                                . http_build_query(['path' => $base64Path, 'type' => 'download']),
                            'fileSize' => File::human_filesize(filesize($absolutePath . DIRECTORY_SEPARATOR . $basename)),
                            'previewUrl' => url()->current() . '?'
                                . http_build_query(['path' => $base64Path]),
                        ];
                    }, array_merge(
                            array_filter(scandir($absolutePath), function ($basename) {
                                return $basename !== "." && $basename !== "..";
                            })
                        )
                    );

                    $backPath = null;
                    if ($extraPath) {
                        $dirname = pathinfo($extraPath)['dirname'];
                        if (empty($dirname) || $dirname === '.') {
                            $backPath = url()->current();
                        } else {
                            $backPath = url()->current()
                                . "?" . http_build_query(['path' => base64_encode($dirname)]);
                        }
                    }

                    return view('preview.list', [
                        'basename' => $shownName,
                        'backPath' => $backPath,
                        'files' => $files,
                    ]);
                }
            }
        }
        return view('preview.fail');
    }
}
