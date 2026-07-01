<?php

declare(strict_types=1);

use App\Models\Visit;

it('records the real client IP from X-Forwarded-For behind a trusted proxy', function (): void {
    $this->get('/', ['X-Forwarded-For' => '203.0.113.50'])->assertOk();

    $visit = Visit::query()->latest('id')->first();

    expect($visit)->not->toBeNull()
        ->and($visit->ip_address)->toBe('203.0.113.50');
});

it('falls back to the direct IP when no forwarded header is present', function (): void {
    $this->get('/')->assertOk();

    $visit = Visit::query()->latest('id')->first();

    expect($visit)->not->toBeNull()
        ->and($visit->ip_address)->not->toBeEmpty();
});
