<?php

namespace App\Http\Controllers;

use App\Services\FileEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Download a decrypted file.
     */
    public function download(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path || !Storage::exists($path)) {
            abort(404);
        }

        $filename = basename($path);
        
        // Determine mime type
        $mimeType = Storage::mimeType($path);
        
        return FileEncryptionService::decryptedResponse($path, $filename, $mimeType);
    }
}
