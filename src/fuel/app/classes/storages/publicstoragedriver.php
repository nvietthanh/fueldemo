<?php

namespace Storages;

use Fuel\Core\Config;
use Storages\Interfaces\StorageDriverInterface;

class PublicStorageDriver implements StorageDriverInterface
{
    protected static ?self $instance = null;
    private $config;

    public function __construct()
    {
        //
    }

    protected static function get_instance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function upload(string $path, array $file): bool
    {
        static::get_instance();

        $fileTmpPath = $file['tmp_name'];
        $fileContents = file_get_contents($fileTmpPath);

        $fullPath = DOCROOT . '/storage/' . $path;
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return file_put_contents($fullPath, $fileContents) !== false;
    }

    public static function delete(string $path): bool
    {
        static::get_instance();

        $fullPath = DOCROOT . '/storage/' . ltrim($path, '/');

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public static function exists(string $path): bool
    {
        static::get_instance();

        $fullPath = DOCROOT . '/storage/' . ltrim($path, '/');

        return file_exists($fullPath);
    }

    public static function url(string $path): string
    {
        static::get_instance();

        $base_url = rtrim(Config::get('base_url'), '/');
        $clean_path = ltrim($path, '/');

        return "{$base_url}/storage/{$clean_path}";
    }
}
