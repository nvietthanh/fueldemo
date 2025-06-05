<?php

namespace Traits;

use Fuel\Core\Config;
use Fuel\Core\Redis_Db;
use Queue_Status;
use Ramsey\Uuid\Uuid;

trait Dispatchable
{
    public static function dispatch(...$args): void
    {
        $redis = Redis_Db::instance();

        $jobClass = static::class;
        $jobId = 'job:' . Uuid::uuid4()->toString();
        $isoDate = date('c');

        $payload = [
            'id'            => $jobId,
            'class'         => $jobClass,
            'args'          => $args,
            'attempts'      => 0,
            'status'        => Queue_Status::STATUS_WAITING,
            'status_times'  => [
                Queue_Status::STATUS_WAITING => $isoDate,
            ],
            'created_at'    => $isoDate,
        ];

        $redis->set($jobId, json_encode($payload));
        $redis->set("{$jobId}:status", Queue_Status::STATUS_WAITING);
        $redis->rpush('queue:default', $jobId);
    }
}
