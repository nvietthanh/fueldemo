<?php

namespace Storages\Interfaces;

interface StorageDriverInterface
{
    public static function upload(string $path, array $file): bool;
    public static function delete(string $path): bool;
    public static function exists(string $path): bool;
    public static function url(string $path): string;
}
