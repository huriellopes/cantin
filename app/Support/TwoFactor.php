<?php

declare(strict_types=1);

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * Utilitários de autenticação em dois fatores (TOTP): segredo, QR code,
 * verificação de código e geração de códigos de recuperação.
 */
final class TwoFactor
{
    public static function generateSecret(): string
    {
        return self::engine()->generateSecretKey();
    }

    /**
     * Verifica um código TOTP contra o segredo (janela de tolerância padrão).
     */
    public static function verify(string $secret, string $code): bool
    {
        $code = (string) preg_replace('/\s+/', '', $code);

        if ($code === '' || $secret === '') {
            return false;
        }

        return (bool) self::engine()->verifyKey($secret, $code);
    }

    /**
     * SVG inline (data URI) do QR code (otpauth) para o app autenticador.
     */
    public static function qrCode(string $holder, string $secret): string
    {
        $issuer = (string) config('app.name', 'CaNTIn');

        $uri = 'otpauth://totp/' . rawurlencode($issuer . ':' . $holder)
            . '?secret=' . $secret
            . '&issuer=' . rawurlencode($issuer)
            . '&algorithm=SHA1&digits=6&period=30';

        $writer = new Writer(new ImageRenderer(new RendererStyle(200, 1), new SvgImageBackEnd()));

        return 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($uri));
    }

    /**
     * Gera códigos de recuperação de uso único.
     *
     * @return array<int, string>
     */
    public static function recoveryCodes(int $amount = 8): array
    {
        return collect(range(1, $amount))
            ->map(fn (): string => Str::upper(Str::random(5) . '-' . Str::random(5)))
            ->all();
    }

    private static function engine(): Google2FA
    {
        return new Google2FA();
    }
}
