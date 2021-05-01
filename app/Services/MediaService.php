<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Exception;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function storeFile(UploadedFile $file): string
    {
        if($file === null) {
            throw new Exception("UploadedFile instance excepted, null given.");
        }

        $path = $file->store('', 'media');
        return $path;
    }

    public function saveTemporarily($input): UploadedFile
    {
        // Decode the base64 file
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $input));

        // Save it to temporary dir first.
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $fileData);

        // This just to help us get file info.
        $tmpFile = new File($tmpFilePath);

        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        return $file;
    }

    public function deleteMedia(Media $media)
    {
        Storage::disk('media')->delete($media->path);
        $media->delete();
    }
}
