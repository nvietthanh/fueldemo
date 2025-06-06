<?php

namespace Fuel\Tasks;

use DateTime;
use Fuel\Core\Cli;
use Fuel\Core\Log;
use Fuel\Core\Redis_Db;
use Fuel\Core\RedisException;
use Queue_Status;
use ReflectionClass;

class Queue
{
    protected $redisClient;

    public function __construct()
    {
        $this->redisClient = Redis_Db::instance();
    }

    public function run(string $queue = 'queue:default')
    {
        Cli::write("[✓] Queue listening on queue: {$queue}");

        while (true) {
            try {
                $jobId = $this->redisClient->lpop($queue);

                if ($jobId) {
                    $jobPayload = $this->redisClient->get($jobId);

                    if (is_string($jobPayload)) {
                        $payload = json_decode($jobPayload, true);

                        $this->executeJob($jobId, $payload);
                    } else {
                        echo "Invalid job data (not string) for job $jobId\n";
                        Log::error("Invalid job data (not string) for job $jobId");
                        $this->redisClient->set("$jobId:status", Queue_Status::STATUS_FAILED);
                    }
                }
            } catch (RedisException $e) {
                Log::error("Redis error: " . $e->getMessage());
                continue;
            } catch (\Exception $e) {
                echo "[✗] Queue error: " . $e->getMessage() . "\n";
                Log::error("[✗] Queue error: " . $e->getMessage());
            }
        }
    }

    private function executeJob(string $jobId, array $payload)
    {
        $jobClass = $payload['class'];
        $args = $payload['args'];

        $this->logJob($jobId, $jobClass, Queue_Status::STATUS_RUNNING);

        if (class_exists($jobClass)) {
            try {
                $reflection = new ReflectionClass($jobClass);
                $job = $reflection->newInstanceArgs($args);

                $job->handle();

                $this->logJob($jobId, $jobClass, Queue_Status::STATUS_COMPLETED);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();

                Log::error("Job $jobId failed: " . $errorMessage);
                $this->logJob($jobId, $jobClass, Queue_Status::STATUS_FAILED);
            }
        } else {
            Cli::write("Job class not found: $jobClass");
            Log::error("Job class not found: $jobClass");

            $this->logJob($jobId, $jobClass, Queue_Status::STATUS_FAILED);
        }
    }

    private function logJob(string $jobId, string $jobClass, string $status)
    {
        $isoDateLog = date('c');
        $dt = new DateTime($isoDateLog);
        $formattedDateLog = $dt->format('Y/m/d H:i:s');

        // Update data payload job
        $jobPayloadJson = $this->redisClient->get($jobId);
        $payload = json_decode($jobPayloadJson, true);
        if (!isset($payload['status_times']) || !is_array($payload['status_times'])) {
            $payload['status_times'] = [];
        }
        $payload['status_times'][$status] = $isoDateLog;

        $this->redisClient->set($jobId, json_encode($payload));

        $this->redisClient->set("$jobId:status", $status);

        if ($status == Queue_Status::STATUS_RUNNING) {
            Cli::write("");
        }
        Cli::write("$jobClass $formattedDateLog\t\t\t\t $status");
    }
}
