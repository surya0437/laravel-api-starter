<?php

namespace Surya\ApiStarter\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ApiRequestFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public Throwable $exception
    ) {}
}
