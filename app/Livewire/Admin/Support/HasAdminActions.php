<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Support;

/**
 * Helpers de UX para os componentes do admin: notificações (toaster),
 * confirmação em modal e modal de visualização.
 */
trait HasAdminActions
{
    /** @var array<string, mixed> */
    public array $confirm = [];

    public bool $showView = false;

    public ?string $viewTitle = null;

    /** @var array<int, array{label: string, value: mixed}> */
    public array $viewData = [];

    /**
     * Dispara um toast no navegador.
     */
    public function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', type: $type, message: $message);
    }

    /**
     * Abre o modal de confirmação para uma ação.
     *
     * @param  array<int, mixed>  $args
     * @param  array<string, mixed>  $options
     */
    public function requestConfirm(string $method, array $args = [], array $options = []): void
    {
        $this->confirm = array_merge([
            'method' => $method,
            'args' => $args,
            'title' => 'Confirmar ação',
            'message' => 'Tem certeza que deseja continuar?',
            'label' => 'Confirmar',
            'danger' => false,
        ], $options);
    }

    public function confirmed(): void
    {
        $confirm = $this->confirm;
        $this->confirm = [];

        if (!empty($confirm['method']) && method_exists($this, $confirm['method'])) {
            $this->{$confirm['method']}(...array_values($confirm['args'] ?? []));
        }
    }

    public function cancelConfirm(): void
    {
        $this->confirm = [];
    }

    // Atalhos de confirmação comuns ------------------------------------------

    public function confirmDelete(int $id): void
    {
        $this->requestConfirm('delete', [$id], [
            'title' => 'Excluir registro',
            'message' => 'Tem certeza que deseja excluir? Esta ação não pode ser desfeita.',
            'label' => 'Excluir',
            'danger' => true,
        ]);
    }

    public function confirmToggle(int $id): void
    {
        $this->requestConfirm('toggleStatus', [$id], [
            'title' => 'Alterar status',
            'message' => 'Deseja alterar o status deste registro?',
            'label' => 'Confirmar',
        ]);
    }
}
