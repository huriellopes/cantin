<?php

declare(strict_types=1);

use App\Livewire\Admin\Users\Index;
use App\Models\ImpersonationLog;
use Livewire\Livewire;

it('lets a super-admin impersonate a regular user (redirects to the site) and audits it', function (): void {
    $super = userWithRole('super-admin');
    $target = userWithRole('user');

    Livewire::actingAs($super)->test(Index::class)
        ->call('impersonate', $target->id)
        ->assertRedirect(route('site.home'));

    expect(session('impersonator_id'))->toBe($super->id)
        ->and(ImpersonationLog::query()->where([
            'impersonator_id' => $super->id,
            'impersonated_id' => $target->id,
            'action' => 'started',
        ])->exists())->toBeTrue();
});

it('sends an impersonated admin to the dashboard (respecting permissions)', function (): void {
    $super = userWithRole('super-admin');
    $admin = userWithRole('admin');

    Livewire::actingAs($super)->test(Index::class)
        ->call('impersonate', $admin->id)
        ->assertRedirect(route('admin.dashboard'));
});

it('returns to the original user when leaving impersonation', function (): void {
    $super = userWithRole('super-admin');
    $target = userWithRole('user');

    $this->actingAs($target)
        ->withSession(['impersonator_id' => $super->id])
        ->post(route('impersonate.leave'))
        ->assertRedirect(route('admin.users.index'));

    expect(auth()->id())->toBe($super->id)
        ->and(session('impersonator_id'))->toBeNull()
        ->and(ImpersonationLog::query()->where('action', 'stopped')->exists())->toBeTrue();
});

it('forbids non super-admins from the impersonation logs', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get(route('admin.impersonation-logs.index'))
        ->assertForbidden();
});

it('shows the impersonation logs page to a super-admin', function (): void {
    $this->actingAs(userWithRole('super-admin'))
        ->get(route('admin.impersonation-logs.index'))
        ->assertOk();
});
