<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Upload file dengan validasi
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param array $allowedMimes
     * @param int $maxSize (dalam bytes)
     * @return string Path file yang tersimpan
     * @throws \Exception
     */
    public static function uploadFile($file, $path = 'uploads', $allowedMimes = ['pdf'], $maxSize = 5242880)
    {
        // Validasi tipe file
        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
            throw new \Exception('Tipe file tidak diizinkan');
        }

        // Validasi ukuran
        if ($file->getSize() > $maxSize) {
            throw new \Exception('Ukuran file terlalu besar');
        }

        // Defence-in-depth: validasi magic bytes PDF (%PDF)
        $handle = fopen($file->getRealPath(), 'rb');
        $header = fread($handle, 4);
        fclose($handle);

        if ($header !== '%PDF') {
            throw new \InvalidArgumentException('The file content does not match the expected PDF format.');
        }

        return $file->store($path, 'public');
    }

    /**
     * Delete file dari storage
     * 
     * @param string $filePath
     * @return bool
     */
    public static function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return true;
    }

    /**
     * Get file URL
     * 
     * @param string $filePath
     * @return string
     */
    public static function getFileUrl($filePath)
    {
        if (!$filePath) {
            return null;
        }

        return asset('storage/' . $filePath);
    }

    /**
     * Format ukuran file dalam MB
     * 
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
