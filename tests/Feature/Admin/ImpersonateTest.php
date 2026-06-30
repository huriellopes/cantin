<?php

declare(strict_types=1);

use App\Models\ImpersonationLog;

it('lets a super-admin impersonate any user and audits it', function () {
    $super = userWithRole('super-admin');
    $target = userWithRole('user');

    $this->actingAs($super)
        ->post(route('admin.users.impersonate', $target->id))
        ->assertRedirect(route('site.home'));

    expect(auth()->id())->toBe($target->id)
        ->and(session('impersonator_id'))->toBe($super->id)
        ->and(ImpersonationLog::query()->where([
            'impersonator_id' => $super->id,
            'impersonated_id' => $target->id,
            'action' => 'started',
        ])->exists())->toBeTrue();
});

it('returns to the original user when leaving impersonation', function () {
    $super = userWithRole('super-admin');
    $target = userWithRole('user');

    $this->actingAs($super)->post(route('admin.users.impersonate', $target->id));
    expect(auth()->id())->toBe($target->id);

    $this->post(route('impersonate.leave'))->assertRedirect(route('admin.users.index'));

    expect(auth()->id())->toBe($super->id)
        ->and(session('impersonator_id'))->toBeNull()
        ->and(ImpersonationLog::query()->where('action', 'stopped')->exists())->toBeTrue();
});

it('forbids non super-admins from impersonating', function () {
    $admin = userWithRole('admin');
    $target = userWithRole('user');

    $this->actingAs($admin)
        ->post(route('admin.users.impersonate', $target->id))
        ->assertForbidden();
});

it('shows the impersonation logs page to a super-admin', function () {
    $this->actingAs(userWithRole('super-admin'))
        ->get(route('admin.impersonation-logs.index'))
        ->assertOk();
});
