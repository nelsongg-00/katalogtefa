<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    /**
     * Safely store an uploaded file to storage, preventing Windows temporary realpath resolution bugs in PHP 8.5+.
     */
    protected function storeUploadedFile(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $sourcePath = $file->getRealPath();
        if (! $sourcePath || ! is_file($sourcePath)) {
            $sourcePath = $file->getPathname();
        }

        if ($sourcePath && is_file($sourcePath)) {
            return Storage::disk($disk)->putFileAs($folder, $sourcePath, $file->hashName());
        }

        return $file->store($folder, $disk);
    }
}
