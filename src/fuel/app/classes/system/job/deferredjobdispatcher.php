<?php

namespace System\Job;

class DeferredJobDispatcher
{
    protected static array $queued = [];

    public static function queue(array $job): void
    {
        static::$queued[] = $job;
    }

    public static function dispatch_all(): void
    {
        foreach (static::$queued as $job) {
            $class_name = $job['class'];
            $args = $job['args'];

            $job = new $class_name(...$args);
            $job->start(...$args);
        }

        static::$queued = [];
    }
}
