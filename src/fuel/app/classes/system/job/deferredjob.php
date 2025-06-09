<?php

namespace System\Job;

abstract class DeferredJob
{
    abstract public function handle(): void;
}
