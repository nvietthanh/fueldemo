<?php

use Factory\Storage;

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false) return $default;

        $value = trim($value);
        switch (strtolower($value)) {
            case 'null':
                return null;
            case 'true':
                return true;
            case 'false':
                return false;
            case 'empty':
                return '';
        }

        return $value;
    }
}

if (!function_exists('get_file_url')) {
    /**
     * Convert file url by file path
     *
     * @param string $filePath
     * @return string|null
     */
    function get_file_url(string $path): ?string
    {
        if ($path && !parse_url($path, PHP_URL_SCHEME)) {
            return Storage::url($path);
        }

        return $path;
    }
}
