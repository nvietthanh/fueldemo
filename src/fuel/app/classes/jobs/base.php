<?php

use System\Job\DeferredJob;
use System\Job\DeferredJobDispatcher;

abstract class Jobs_Base extends DeferredJob
{
    public static function dispatch(...$args): void
    {
        $job_data = [
            'class' => static::class,
            'args' => $args,
        ];

        if (self::isHttpRequest()) {
            DeferredJobDispatcher::queue($job_data);
        } else {
            $job = new static(...$args);
            $job->start(...$args);
        }
    }

    protected static function isHttpRequest(): bool
    {
        return php_sapi_name() !== 'cli';
    }
}
