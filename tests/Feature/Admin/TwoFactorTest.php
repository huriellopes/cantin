<?php

declare(strict_types=1);

use App\Livewire\Admin\Profile\Index as ProfileIndex;
use App\Livewire\Site\Auth\TwoFactorChallenge;
use App\Models\User;
use App\Support\TwoFactor;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

function userWithTwoFactor(string $password = 'senha-forte-123'): User
{
    $user = userWithRole('admin');
    $user->forceFill([
        'password' => $password,
        'password_change_required' => false,
        'two_factor_secret' => TwoFactor::generateSecret(),
        'two_factor_recovery_codes' => ['AAAAA-BBBBB', 'CCCCC-DDDDD'],
        'two_factor_confirmed_at' => now(),
    ])->save();

    return $user->fresh();
}

it('challenges for 2FA at login instead of logging in', function (): void {
    $user = userWithTwoFactor();

    $this->post(route('site.auth.login.post'), [
        'email' => $user->email,
        'password' => 'senha-forte-123',
    ])->assertRedirect(route('site.auth.two-factor'));

    $this->assertGuest();
    expect(session('login.2fa')['id'])->toBe($user->id);
});

it('logs in with a valid TOTP code', function (): void {
    $user = userWithTwoFactor();
    session()->put('login.2fa', ['id' => $user->id, 'remember' => false]);

    $otp = (new Google2FA())->getCurrentOtp($user->two_factor_secret);

    Livewire::test(TwoFactorChallenge::class)
        ->set('code', $otp)
        ->call('verify')
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user->fresh());
    expect(session('login.2fa'))->toBeNull();
});

it('rejects an invalid TOTP code', function (): void {
    $user = userWithTwoFactor();
    session()->put('login.2fa', ['id' => $user->id, 'remember' => false]);

    Livewire::test(TwoFactorChallenge::class)
        ->set('code', '000000')
        ->call('verify')
        ->assertHasErrors('code');

    $this->assertGuest();
});

it('logs in with a recovery code and consumes it', function (): void {
    $user = userWithTwoFactor();
    session()->put('login.2fa', ['id' => $user->id, 'remember' => false]);

    Livewire::test(TwoFactorChallenge::class)
        ->set('useRecovery', true)
        ->set('recovery_code', 'AAAAA-BBBBB')
        ->call('verify')
        ->assertRedirect(route('admin.dashboard'));

    expect($user->fresh()->recoveryCodes())->not->toContain('AAAAA-BBBBB');
});

it('enables and confirms 2FA from the profile', function (): void {
    $user = userWithRole('admin');
    $user->forceFill(['password_change_required' => false])->save();

    $component = Livewire::actingAs($user)->test(ProfileIndex::class)
        ->call('enableTwoFactor')
        ->assertSet('showTwoFactorSetup', true);

    $otp = (new Google2FA())->getCurrentOtp($user->fresh()->two_factor_secret);

    $component->set('form.two_factor_code', $otp)->call('confirmTwoFactor');

    expect($user->fresh()->hasTwoFactorEnabled())->toBeTrue();
});

it('disables 2FA from the profile', function (): void {
    $user = userWithTwoFactor();

    Livewire::actingAs($user)->test(ProfileIndex::class)->call('disableTwoFactor');

    expect($user->fresh()->hasTwoFactorEnabled())->toBeFalse();
});

it('shows and hides recovery codes from the profile', function (): void {
    $user = userWithTwoFactor();

    Livewire::actingAs($user)->test(ProfileIndex::class)
        ->assertSet('recoveryCodes', [])
        ->call('showRecoveryCodes')
        ->assertSet('recoveryCodes', ['AAAAA-BBBBB', 'CCCCC-DDDDD'])
        ->call('hideRecoveryCodes')
        ->assertSet('recoveryCodes', []);
});

it('does not lock out login when the 2FA secret cannot be decrypted', function (): void {
    $user = userWithTwoFactor('senha-forte-123');

    // Simula APP_KEY trocada: grava um valor "encriptado" ilegível direto no banco.
    DB::table('users')->where('id', $user->id)->update([
        'two_factor_secret' => 'valor-invalido-nao-decifravel',
    ]);

    // 2FA é tratado como inativo (falha aberta) — sem DecryptException/500.
    expect($user->fresh()->hasTwoFactorEnabled())->toBeFalse();

    $this->post(route('site.auth.login.post'), [
        'email' => $user->email,
        'password' => 'senha-forte-123',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user->fresh());
});
