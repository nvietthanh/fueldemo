<?php

namespace Helpers;

use Factory\Storage;

class FileHelper
{
    /**
     * Handles upload file
     *
     * @param array  $file
     *
     * @return string
     */
    public static function upload(array $file, string $folder_path): string
    {
        $storage_path = self::createPath($file, $folder_path);

        $success = Storage::upload($storage_path, $file);

        if (!$success) {
            throw new \RuntimeException('Failed to upload file to storage.');
        }

        return $storage_path;
    }

    private static  function createPath(array $file, string $folder_path): string
    {
        $year = date('Y');
        $month = date('m');

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $random = bin2hex(random_bytes(20));
        $timestamp = time();
        $uniqueName = "{$random}_{$timestamp}.{$extension}";

        return rtrim($folder_path, '/') . "/{$year}/{$month}/{$uniqueName}";
    }
}
