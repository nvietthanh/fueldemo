<?php

namespace Storages;

use Aws\S3\S3Client;
use Fuel\Core\Config;
use Fuel\Core\File;
use Storages\Interfaces\StorageDriverInterface;

class S3StorageDriver implements StorageDriverInterface
{
    protected $client;
    private $config;
    protected static ?self $instance = null;

    public function __construct()
    {
        Config::load('disk', true);
        $this->config = Config::get('disk.s3');

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $this->config['region'],
            'endpoint' => $this->config['endpoint'],
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $this->config['key'],
                'secret' => $this->config['secret'],
            ],
        ]);
    }

    protected static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function upload(string $path, array $file): bool
    {
        $instance = static::getInstance();

        $fileTmpPath = $file['tmp_name'];
        $fileContents = file_get_contents($fileTmpPath);
        $mimeType = mime_content_type($fileTmpPath);

        try {
            $instance->client->putObject([
                'Bucket' => $instance->config['bucket'],
                'Key' => $path,
                'Body' => $fileContents,
                'ContentType' => $mimeType ?? 'application/octet-stream',
                'ACL' => 'public-read',
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function delete(string $path): bool
    {
        $instance = static::getInstance();

        try {
            $instance->client->deleteObject([
                'Bucket' => $instance->config['bucket'],
                'Key'    => ltrim($path, '/'),
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function exists(string $path): bool
    {
        $instance = static::getInstance();

        try {
            return $instance->client->doesObjectExist(
                $instance->config['bucket'],
                ltrim($path, '/')
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function url(string $path): string
    {
        $instance = static::getInstance();

        $endpoint = rtrim($instance->config['endpoint'], '/');
        $bucket   = trim($instance->config['bucket'], '/');
        $cleanPath = ltrim($path, '/');

        return "{$endpoint}/{$bucket}/{$cleanPath}";
    }
}
