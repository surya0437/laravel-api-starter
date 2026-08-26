<?php

use Illuminate\Http\JsonResponse;
use Surya\ApiStarter\Traits\HasApiResponse;

beforeEach(function () {
    $this->responseHelper = new class {
        use HasApiResponse;
    };
});

test('success response structure', function () {
    $response = $this->responseHelper->success(['id' => 1], 'Success test');

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(200);

    $data = $response->getData(true);
    expect($data['success'])->toBeTrue()
        ->and($data['message'])->toBe('Success test')
        ->and($data['data'])->toBe(['id' => 1]);
});

test('created response status is 201', function () {
    $response = $this->responseHelper->created(['id' => 2]);
    expect($response->getStatusCode())->toBe(201);
});

test('error response structure', function () {
    $response = $this->responseHelper->error('Error test', 400, ['field' => ['Invalid']]);

    expect($response->getStatusCode())->toBe(400);

    $data = $response->getData(true);
    expect($data['success'])->toBeFalse()
        ->and($data['message'])->toBe('Error test')
        ->and($data['errors'])->toBe(['field' => ['Invalid']]);
});
