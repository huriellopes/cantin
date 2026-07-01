<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * Form object do perfil do admin. Reúne os campos das três ações (dados,
 * troca de senha e exclusão de conta); a validação é feita por ação no
 * componente, pois cada uma valida um subconjunto com regras dependentes do
 * usuário autenticado (unique/current_password).
 */
class ProfileForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $delete_password = '';
}
