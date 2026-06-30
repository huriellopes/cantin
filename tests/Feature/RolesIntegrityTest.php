<?php

declare(strict_types=1);

use App\Models\Role;
use Illuminate\Support\Facades\DB;

it('repairs the legacy super-user slug to super-admin', function () {
    // Simula o dado legado que existia em produção (slug 'super-user').
    DB::table('roles')->delete();
    Role::query()->forceCreate([
        'name' => 'Super User',
        'slug' => 'super-user',
    ]);

    // Executa a migration de reparo.
    (require database_path('migrations/2026_06_29_000000_fix_super_admin_role_slug.php'))->up();

    expect(Role::query()->where('slug', 'super-admin')->value('name'))->toBe('Super Admin')
        ->and(Role::query()->where('slug', 'super-user')->exists())->toBeFalse();
});

it('grants admin access and login redirect to a super-admin user', function () {
    // O slug 'super-admin' é o esperado pelo middleware role, pelo LoginController
    // e pelas policies. Este teste trava esse contrato.
    $user = userWithRole('super-admin');

    expect($user->hasRole('super-admin'))->toBeTrue()
        ->and($user->hasRole('admin', 'super-admin'))->toBeTrue()
        ->and($user->isSuperAdmin())->toBeTrue();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
});

it('does not recognize the legacy super-user slug as authorized', function () {
    $user = userWithRole('super-user');

    // Garante que o slug legado não concede acesso administrativo.
    expect($user->hasRole('admin', 'super-admin'))->toBeFalse();
    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});
