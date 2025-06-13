<?php

class Controller_Admin_Job extends Controller_Base
{
	protected $redisClient;

	public function before()
	{
		parent::before();

		$this->redisClient = Redis_Db::instance();
	}

	public function action_list()
	{
		$reflect = new ReflectionClass(Queue_Status::class);
		$statuses = $reflect->getConstants();

		$result = [];

		foreach ($statuses as $status) {
			$jobs = $this->getJobsByStatus($status);

			$result[$status] = $jobs;
		}

		return $this->json_response([
		    'status' => $status,
		    'result'   => $result,
		]);
	}

	protected function getJobsByStatus(string $status): array
	{
		$keys = $this->redisClient->keys('job:*:status');

		$jobIds = [];

		foreach ($keys as $key) {
			$jobStatus = $this->redisClient->get($key);

			if ($jobStatus === $status) {
				$jobId = substr($key, 0, -7);
				$jobPayload = $this->redisClient->get($jobId);
				$payload = json_decode($jobPayload, true);

				if ($payload) {
					$jobIds[] = array_merge($payload, [
						'status' => $jobStatus,
					]);
				}
			}
		}

		return $jobIds;
	}
}
