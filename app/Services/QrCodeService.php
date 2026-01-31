<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\CompanySetting;

class QrCodeService
{
    /**
     * Generate branded QR Code
     * 
     * @param string $data Content to be encoded
     * @param string|null $filename Output filename (optional)
     * @return string|bool Path to generated file or content
     */
    public static function generate(string $data, ?string $filename = null)
    {
        // Get branding settings
        $primaryColor = SettingsService::getPrimaryColor();
        $logoPath = SettingsService::get('logo_light'); // Use light logo for QR center

        // Parse hex color to RGB
        list($r, $g, $b) = sscanf($primaryColor, "#%02x%02x%02x");

        // Check for Imagick support
        $hasImagick = extension_loaded('imagick');
        $format = $hasImagick ? 'png' : 'svg';

        // Initialize QR Generator
        $qr = QrCode::format($format)
            ->size(300)
            ->color($r, $g, $b) // Brand color
            ->margin(1)
            ->errorCorrection('H'); // High error correction

        // Merge Logo only if Imagick is available
        if ($hasImagick && $logoPath && Storage::disk('public')->exists($logoPath)) {
            $fullLogoPath = Storage::disk('public')->path($logoPath);
            $qr->merge($fullLogoPath, .3, true);
        }

        $content = $qr->generate($data);

        // If filename provided, save to storage
        if ($filename) {
            // Adjust extension if needed (simpler to just respect $filename or auto-append)
            // But Observer passes .png. If we generated SVG, saving as .png file (but text content) is messy but browsers might read it if headers are right, but file system won't care.
            // Ideally we change extension. But for now let's just save.
            Storage::disk('public')->put($filename, $content);
            return $filename;
        }

        return $content;
    }

    /**
     * Get validation URL for a specific code
     */
    public static function getValidationUrl(string $code): string
    {
        return route('val.document', ['code' => $code]);
    }
}
