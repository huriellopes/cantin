<?php

declare(strict_types=1);

use App\Livewire\Admin\Audits\Index;
use App\Models\Audit;
use App\Models\Terreiro;
use Livewire\Livewire;

it('lets a super-admin open the audits page', function (): void {
    $this->actingAs(userWithRole('super-admin'))
        ->get(route('admin.audits.index'))
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('denies a regular admin access to audits', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get(route('admin.audits.index'))
        ->assertForbidden();
});

it('opens the detail modal with the changed values', function (): void {
    $audit = Audit::query()->create([
        'user_type' => null,
        'user_id' => null,
        'event' => 'updated',
        'auditable_type' => (new Terreiro())->getMorphClass(),
        'auditable_id' => 1,
        'old_values' => ['name' => 'Antigo'],
        'new_values' => ['name' => 'Novo'],
        'url' => 'http://localhost/admin/terreiros',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PestTest',
        'tags' => null,
    ]);

    Livewire::actingAs(userWithRole('super-admin'))->test(Index::class)
        ->call('view', $audit->id)
        ->assertSet('showModal', true)
        ->assertSet('modified', [
            ['field' => 'name', 'old' => 'Antigo', 'new' => 'Novo'],
        ]);
});
