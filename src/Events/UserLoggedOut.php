<?php

namespace Surya\ApiStarter\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedOut
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Authenticatable $user
    ) {}
}
