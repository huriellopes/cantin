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
            'title' => __('msg_admin_actions.confirm_title'),
            'message' => __('msg_admin_actions.confirm_message'),
            'label' => __('msg_admin_actions.confirm_label'),
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
            'title' => __('msg_admin_actions.delete_title'),
            'message' => __('msg_admin_actions.delete_message'),
            'label' => __('msg_admin_actions.delete_label'),
            'danger' => true,
        ]);
    }

    public function confirmToggle(int $id): void
    {
        $this->requestConfirm('toggleStatus', [$id], [
            'title' => __('msg_admin_actions.toggle_title'),
            'message' => __('msg_admin_actions.toggle_message'),
            'label' => __('msg_admin_actions.confirm_label'),
        ]);
    }
}
