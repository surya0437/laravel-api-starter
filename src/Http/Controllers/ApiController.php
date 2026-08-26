<?php

namespace Surya\ApiStarter\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Surya\ApiStarter\Contracts\ApiResponseContract;
use Surya\ApiStarter\Traits\HasApiResponse;

abstract class ApiController extends BaseController implements ApiResponseContract
{
    use HasApiResponse;
}
