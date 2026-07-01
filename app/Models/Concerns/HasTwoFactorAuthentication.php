<?php

declare(strict_types=1);

namespace App\Models\Concerns;

/**
 * Autenticação em dois fatores (TOTP). O segredo e os códigos de recuperação
 * são armazenados encriptados e ocultos na serialização.
 */
trait HasTwoFactorAuthentication
{
    public function initializeHasTwoFactorAuthentication(): void
    {
        $this->mergeCasts([
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ]);

        $this->makeHidden(['two_factor_secret', 'two_factor_recovery_codes']);
    }

    /**
     * 2FA ativo somente quando há segredo E foi confirmado com um código válido.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * @return array<int, string>
     */
    public function recoveryCodes(): array
    {
        return is_array($this->two_factor_recovery_codes) ? $this->two_factor_recovery_codes : [];
    }

    /**
     * Consome um código de recuperação (uso único). Retorna false se inválido.
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->recoveryCodes();

        if (!in_array($code, $codes, true)) {
            return false;
        }

        $this->forceFill([
            'two_factor_recovery_codes' => array_values(array_diff($codes, [$code])),
        ])->save();

        return true;
    }
}
