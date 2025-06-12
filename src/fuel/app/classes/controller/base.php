<?php

use System\Job\DeferredJobDispatcher;
use Traits\ResponseTrait;

class Controller_Base extends Controller_Template
{
    use ResponseTrait;

    public function after($response)
    {
        DeferredJobDispatcher::dispatch_all();

        return parent::after($response);
    }
}
