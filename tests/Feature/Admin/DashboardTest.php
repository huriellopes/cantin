<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard;
use App\Services\DashboardStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects guests to the site login', function (): void {
    $this->get('/admin')->assertRedirect(route('site.auth.login'));
});

it('renders the dashboard for an admin', function (): void {
    $this->actingAs(userWithRole('super-admin'))
        ->get('/admin')
        ->assertOk()
        ->assertSee('Painel')
        ->assertSeeLivewire(Dashboard::class);
});

it('filters the charts by period (default 30 days)', function (): void {
    $seriesCount = fn (int $expected): Closure => fn (array $charts): bool => $charts[0]['series']->count() === $expected;

    Livewire::actingAs(userWithRole('super-admin'))->test(Dashboard::class)
        ->assertSet('period', 30)
        ->assertViewHas('charts', $seriesCount(30))
        ->call('setPeriod', 7)
        ->assertSet('period', 7)
        ->assertViewHas('charts', $seriesCount(7))
        ->call('setPeriod', 1)
        ->assertViewHas('charts', $seriesCount(1))
        // valor inválido cai para o padrão (30)
        ->call('setPeriod', 999)
        ->assertSet('period', 30);
});

it('caches the dashboard stats via the scheduled command', function (): void {
    Cache::forget(DashboardStatsService::CACHE_KEY);

    $this->artisan('dashboard:refresh')->assertSuccessful();

    expect(Cache::has(DashboardStatsService::CACHE_KEY))->toBeTrue();

    $data = Cache::get(DashboardStatsService::CACHE_KEY);
    expect($data)->toHaveKeys(['generated_at', 'counts', 'series'])
        ->and($data['series']['visits'])->toHaveCount(DashboardStatsService::WINDOW_DAYS);
});
