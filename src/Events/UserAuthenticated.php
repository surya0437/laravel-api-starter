<?php

namespace Surya\ApiStarter\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAuthenticated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Authenticatable $user,
        public ?string $token = null
    ) {}
}
