<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * Form object do perfil do admin. Reúne os campos das ações (dados, troca de
 * senha, exclusão de conta e confirmação do 2FA); a validação é feita por ação
 * no componente, pois cada uma valida um subconjunto com regras dependentes do
 * usuário autenticado (unique/current_password) ou do segredo TOTP.
 */
class ProfileForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $delete_password = '';

    public string $two_factor_code = '';
}
