<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileEncryptionService
{
    /**
     * Encrypt and store an uploaded file.
     */
    public static function encryptAndStore(UploadedFile $file, string $directory): string
    {
        $realPath = $file->getRealPath();
        $mimeType = $file->getMimeType();
        $filename = $file->hashName();
        
        // Handle Image Optimization/Conversion to WebP
        if (str_starts_with($mimeType, 'image/') && in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            $image = null;
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                $image = imagecreatefromjpeg($realPath);
            } elseif ($mimeType === 'image/png') {
                $image = imagecreatefrompng($realPath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }

            if ($image) {
                $tempPath = tempnam(sys_get_temp_dir(), 'webp');
                imagewebp($image, $tempPath, 80); // 80 quality
                imagedestroy($image);
                
                $content = file_get_contents($tempPath);
                unlink($tempPath);
                
                // Change extension to webp for the hash name if we want, 
                // but hashName is already random. Let's just keep it or adjust it.
                $filename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            } else {
                $content = file_get_contents($realPath);
            }
        } else {
            $content = file_get_contents($realPath);
        }

        $encryptedContent = Crypt::encrypt($content);
        $path = $directory . '/' . $filename;
        
        Storage::put($path, $encryptedContent);
        
        return $path;
    }

    /**
     * Decrypt a stored file and return its content.
     */
    public static function decrypt(string $path): ?string
    {
        if (!Storage::exists($path)) {
            return null;
        }

        $encryptedContent = Storage::get($path);
        
        try {
            return Crypt::decrypt($encryptedContent);
        } catch (\Exception $e) {
            // If decryption fails, maybe it's not encrypted? 
            // Return as is or handle error.
            return $encryptedContent;
        }
    }

    /**
     * Get a decrypted response for a file.
     */
    public static function decryptedResponse(string $path, string $filename, string $mimeType)
    {
        $decryptedContent = self::decrypt($path);

        if (!$decryptedContent) {
            abort(404);
        }

        return response($decryptedContent)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
