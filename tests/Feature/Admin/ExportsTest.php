<?php

declare(strict_types=1);

use App\Exports\TerreirosExport;
use App\Exports\UsersExport;
use App\Jobs\FinalizeExport;
use App\Livewire\Admin\Terreiros\Index as TerreirosIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Models\Export;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

it('queues a users xlsx export and registers it as processing', function () {
    Excel::fake();
    $super = userWithRole('super-admin');

    Livewire::actingAs($super)->test(UsersIndex::class)->call('export');

    $export = Export::query()->where('user_id', $super->id)->first();
    expect($export)->not->toBeNull()
        ->and($export->status)->toBe('processing');

    Excel::assertQueued($export->path, 'local', fn ($e) => $e instanceof UsersExport);
});

it('queues a terreiros xlsx export', function () {
    Excel::fake();

    Livewire::actingAs(userWithRole('admin'))->test(TerreirosIndex::class)->call('export');

    $export = Export::query()->first();
    Excel::assertQueued($export->path, 'local', fn ($e) => $e instanceof TerreirosExport);
});

it('marks an export ready and notifies Telegram on finalize', function () {
    config(['services.telegram.token' => 'T', 'services.telegram.chat' => '-1', 'services.telegram.thread_alerts' => '951']);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    $user = userWithRole('super-admin');
    $export = Export::query()->create([
        'user_id' => $user->id, 'name' => 'Usuários', 'disk' => 'local',
        'path' => 'exports/x.xlsx', 'status' => 'processing',
    ]);

    (new FinalizeExport($export->id))->handle();

    expect($export->fresh()->status)->toBe('ready');
    Http::assertSent(fn ($r) => str_contains($r->url(), '/sendMessage'));
});

it('downloads the file and removes it from the server', function () {
    Storage::fake('local');
    $user = userWithRole('super-admin');
    Storage::disk('local')->put('exports/x.xlsx', 'conteudo');

    $export = Export::query()->create([
        'user_id' => $user->id, 'name' => 'Usuários', 'disk' => 'local',
        'path' => 'exports/x.xlsx', 'status' => 'ready',
    ]);

    $this->actingAs($user)
        ->get(route('admin.exports.download', $export->id))
        ->assertOk();

    expect(Export::query()->whereKey($export->id)->exists())->toBeFalse();
});

it('forbids downloading another users export', function () {
    Storage::fake('local');
    $owner = userWithRole('super-admin');
    $other = userWithRole('admin');
    Storage::disk('local')->put('exports/y.xlsx', 'x');

    $export = Export::query()->create([
        'user_id' => $owner->id, 'name' => 'X', 'disk' => 'local',
        'path' => 'exports/y.xlsx', 'status' => 'ready',
    ]);

    $this->actingAs($other)
        ->get(route('admin.exports.download', $export->id))
        ->assertForbidden();
});
