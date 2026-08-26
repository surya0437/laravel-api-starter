<?php

test('contracts are interfaces', function () {
    expect('Surya\ApiStarter\Contracts')
        ->toBeInterfaces();
});

test('controllers extend ApiController', function () {
    expect('Surya\ApiStarter\Http\Controllers')
        ->toExtend('Surya\ApiStarter\Http\Controllers\ApiController');
});

test('exceptions extend ApiException', function () {
    expect('Surya\ApiStarter\Exceptions')
        ->toExtend('Surya\ApiStarter\Exceptions\ApiException');
});
