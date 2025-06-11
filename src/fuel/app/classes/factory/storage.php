<?php

namespace Factory;

use Fuel\Core\Config;
use Storages\Interfaces\StorageDriverInterface;
use Storages\PublicStorageDriver;
use Storages\S3StorageDriver;

class Storage
{
    protected static $disks = [];

    public static function disk(string $name): StorageDriverInterface
    {
        if (!isset(self::$disks[$name])) {
            switch ($name) {
                case 's3':
                    self::$disks[$name] = new S3StorageDriver();
                    break;
                case 'public':
                    self::$disks[$name] = new PublicStorageDriver();
                    break;
                default:
                    throw new \Exception("Storage disk [{$name}] not found.");
            }
        }

        return self::$disks[$name];
    }

    public static function __callStatic(string $method, array $arguments)
    {
        Config::load('app', true);
        $config = Config::get('app');

        return static::disk($config['filesystem_disk'])->$method(...$arguments);
    }
}
