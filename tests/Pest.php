<?php

use Surya\ApiStarter\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case Configuration
|--------------------------------------------------------------------------
*/

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Custom Expectations & Helpers
|--------------------------------------------------------------------------
*/

expect()->extend('toBeApiSuccess', function () {
    return $this->toHaveKey('success', true);
});
