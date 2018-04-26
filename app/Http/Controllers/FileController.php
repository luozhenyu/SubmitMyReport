<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    const UPLOAD_MAX_SIZE = 10 * 1024 * 1024;

    public function store(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $uploadFile = $request->file('upload');
        if (!$uploadFile || !$uploadFile->isValid()) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => '文件上传失败，原因可能是文件过大',
                ],
            ]);
        }

        $size = $uploadFile->getSize();
        if ($size <= 0 || $size > static::UPLOAD_MAX_SIZE) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => '文件超过最大允许长度' . static::uploadLimitHit(),
                ],
            ]);
        }

        $fileName = $uploadFile->getClientOriginalName();
        if (strlen($fileName) > 128) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => '文件名最多为128字符',
                ],
            ]);
        }

        $storedFile = $user->storeFile($uploadFile, $fileName);

        return response()->json(
            ['uploaded' => true] + $storedFile->info()
        );
    }

    /**
     * 大小限制提示
     * @return string
     */
    public static function uploadLimitHit()
    {
        $limit = round(static::UPLOAD_MAX_SIZE / 1024 / 1024, 2);
        return "[大小 ≤{$limit}MB]";
    }

    public function show(Request $request, $random)
    {
        $storedFile = File::where('random', $random)->first();
        abort_if(empty($storedFile), 404);

        $filename = $storedFile->filename;
        $sha512 = $storedFile->sha512;
        $storagePath = File::hashToPath($sha512) . DIRECTORY_SEPARATOR . $sha512;

        return response()->download(Storage::path($storagePath), $filename);
    }
}
