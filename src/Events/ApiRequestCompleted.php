<?php

namespace Surya\ApiStarter\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public Response $response
    ) {}
}
