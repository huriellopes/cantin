<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Profile;

use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Forms\ProfileForm;
use App\Models\User;
use App\Support\TwoFactor;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.admin')]
#[Title('Meu perfil')]
class Index extends Component
{
    use HasAdminActions;

    public ProfileForm $form;

    public bool $showDelete = false;

    // 2FA (setup)
    public bool $showTwoFactorSetup = false;

    public string $qrCode = '';

    /** @var array<int, string> */
    public array $recoveryCodes = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->name = $user->name;
        $this->form->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $data = $this->form->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);
        $this->notify(__('admin.profile.updated'));
    }

    public function updatePassword(): void
    {
        $this->form->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($this->form->password)]);

        $this->form->reset(['current_password', 'password', 'password_confirmation']);
        $this->notify(__('admin.profile.password_changed'));
    }

    public function confirmDeleteAccount(): void
    {
        $this->form->reset('delete_password');
        $this->resetValidation();
        $this->showDelete = true;
    }

    public function deleteAccount()
    {
        $this->form->validate(
            ['delete_password' => ['required', 'current_password']],
            ['delete_password.current_password' => __('admin.profile.wrong_password')],
        );

        $user = Auth::user();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        User::query()->whereKey($user->id)->delete();

        return to_route('site.home');
    }

    /**
     * Inicia a ativação do 2FA: gera segredo + códigos de recuperação (ainda
     * NÃO confirmado) e exibe o QR para o app autenticador.
     */
    public function enableTwoFactor(): void
    {
        $user = Auth::user();
        $secret = TwoFactor::generateSecret();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => TwoFactor::recoveryCodes(),
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->qrCode = TwoFactor::qrCode($user->email, $secret);
        $this->form->reset('two_factor_code');
        $this->recoveryCodes = [];
        $this->showTwoFactorSetup = true;
        $this->resetValidation();
    }

    /**
     * Confirma o 2FA validando um primeiro código do app; só então fica ativo.
     */
    public function confirmTwoFactor(): void
    {
        $user = Auth::user();

        if (!TwoFactor::verify((string) $user->twoFactorSecret(), $this->form->two_factor_code)) {
            $this->form->addError('two_factor_code', __('two_factor.invalid_code'));

            return;
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();

        $this->showTwoFactorSetup = false;
        $this->qrCode = '';
        $this->form->reset('two_factor_code');
        $this->recoveryCodes = $user->recoveryCodes();
        $this->notify(__('two_factor.enabled'));
    }

    public function disableTwoFactor(): void
    {
        Auth::user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->reset('showTwoFactorSetup', 'qrCode', 'recoveryCodes');
        $this->form->reset('two_factor_code');
        $this->notify(__('two_factor.disabled'));
    }

    /**
     * Carrega os códigos de recuperação para exibição (2FA já ativo).
     */
    public function showRecoveryCodes(): void
    {
        $this->recoveryCodes = Auth::user()->recoveryCodes();
    }

    /**
     * Baixa os códigos de recuperação em .txt — gerado sob demanda e transmitido
     * direto ao navegador (nada é gravado no servidor).
     */
    public function downloadRecoveryCodesTxt(): StreamedResponse
    {
        $codes = Auth::user()->recoveryCodes();

        return response()->streamDownload(function () use ($codes): void {
            echo "CaNTIn — Códigos de recuperação (2FA)\n";
            echo "Guarde em local seguro. Cada código pode ser usado uma única vez.\n\n";
            echo implode("\n", $codes) . "\n";
        }, 'cantin-2fa-recovery-codes.txt', ['Content-Type' => 'text/plain']);
    }

    /**
     * Baixa os códigos de recuperação em .png (gerado sob demanda com
     * intervention/image e transmitido direto ao navegador; nada fica no servidor).
     */
    public function downloadRecoveryCodesPng(): StreamedResponse
    {
        $codes = Auth::user()->recoveryCodes();

        $lineHeight = 34;
        $paddingX = 40;
        $top = 60;
        $width = 460;
        $height = $top + max(1, count($codes)) * $lineHeight + 30;

        $image = Image::createImage($width, $height)->fill('ffffff');

        $image->text('CaNTIn - Codigos de recuperacao 2FA', $paddingX, 26, function (FontFactory $font): void {
            $font->size(5);
            $font->color('7c3aed');
            $font->align('left', 'top');
        });

        $y = $top;

        foreach ($codes as $code) {
            $image->text((string) $code, $paddingX, $y, function (FontFactory $font): void {
                $font->size(5);
                $font->color('1e1e2d');
                $font->align('left', 'top');
            });
            $y += $lineHeight;
        }

        $png = (string) $image->encode(new PngEncoder());

        return response()->streamDownload(function () use ($png): void {
            echo $png;
        }, 'cantin-2fa-recovery-codes.png', ['Content-Type' => 'image/png']);
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.profile.index');
    }
}
