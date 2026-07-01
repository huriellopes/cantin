<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Contracts\Encryption\DecryptException;

/**
 * Autenticação em dois fatores (TOTP). O segredo e os códigos de recuperação
 * são armazenados encriptados (APP_KEY) e ocultos na serialização.
 *
 * Resiliência a perda/rotação do APP_KEY: se o valor encriptado não puder ser
 * decifrado, tratamos como "sem 2FA" (falha aberta) em vez de estourar
 * DecryptException — assim uma rotação de chave não tranca todos os usuários
 * com um 500 no login (a senha continua obrigatória).
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
     * Segredo TOTP decifrado, ou null se ausente/indecifrável (APP_KEY trocada).
     */
    public function twoFactorSecret(): ?string
    {
        try {
            $secret = $this->two_factor_secret;
        } catch (DecryptException) { // @phpstan-ignore catch.neverThrown (o cast encriptado lança em runtime se o APP_KEY mudar)
            return null;
        }

        return is_string($secret) && $secret !== '' ? $secret : null;
    }

    /**
     * 2FA ativo somente quando há segredo decifrável E foi confirmado.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->twoFactorSecret()) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * @return array<int, string>
     */
    public function recoveryCodes(): array
    {
        try {
            $codes = $this->two_factor_recovery_codes;
        } catch (DecryptException) { // @phpstan-ignore catch.neverThrown (o cast encriptado lança em runtime se o APP_KEY mudar)
            return [];
        }

        return is_array($codes) ? $codes : [];
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
