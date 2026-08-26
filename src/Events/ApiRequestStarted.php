<?php

namespace Surya\ApiStarter\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class ApiRequestStarted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Request $request) {}
}
