<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function getRandomFileName(string $extension): string
    {
        return preg_replace('/[+=\/]/', random_int(0, 9), base64_encode(random_bytes(8))).'.'.$extension;
    }

    public function moveFile(?UploadedFile $imageFile, string $targetDirectory): ?string
    {
        if ($imageFile != null) {

            $filename = $this->getRandomFileName($imageFile->getClientOriginalExtension());

            $imageFile->move('assets/images/'.$targetDirectory, $filename);

            return $filename;
        } else {
            return null;
        }
    }
}