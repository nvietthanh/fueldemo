<?php

namespace Traits;

use Fuel\Core\Redis_Db;
use Queue_Status;
use Ramsey\Uuid\Uuid;

trait Queueable
{
    public function start(...$args): void
    {
        $redis = Redis_Db::instance();

        $job_class = static::class;
        $job_id = 'job:' . Uuid::uuid4()->toString();
        $iso_date = date('c');

        $payload = [
            'id'            => $job_id,
            'class'         => $job_class,
            'args'          => $args,
            'attempts'      => 0,
            'status'        => Queue_Status::STATUS_WAITING,
            'status_times'  => [
                Queue_Status::STATUS_WAITING => $iso_date,
            ],
            'created_at'    => $iso_date,
        ];

        $redis->set($job_id, json_encode($payload));
        $redis->set("{$job_id}:status", Queue_Status::STATUS_WAITING);
        $redis->rpush('queue:default', $job_id);
    }
}
