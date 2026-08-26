<?php

test('request id middleware adds header to response', function () {
    $response = $this->getJson('/api/v1/health');

    $response->assertHeader('X-Request-Id');
    expect($response->headers->get('X-Request-Id'))->not->toBeEmpty();
});
