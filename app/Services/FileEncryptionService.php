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
        $content = file_get_contents($file->getRealPath());
        $encryptedContent = Crypt::encrypt($content);
        
        $filename = $file->hashName();
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
