<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Models\ImpersonationLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Usuários')]
class Index extends Component
{
    use HasAdminActions, WithDataTable, WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    public ?string $generatedPassword = null;

    public ?string $generatedFor = null;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'email', 'role_id']);
        $this->role_id = Role::query()->where('slug', 'user')->value('id');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->getRawOriginal('role_id');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            User::query()->whereKey($this->editingId)->update($data);
        } else {
            User::query()->create([
                ...$data,
                'slug' => Str::slug($this->name) . '-' . Str::random(5),
                'password' => bcrypt(Str::password(12)),
            ]);
        }

        $message = $this->editingId ? __('msg_users.user_updated') : __('msg_users.user_created');
        $this->showModal = false;
        $this->notify($message);
    }

    public function view(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_users.label_name'), 'value' => $user->name],
            ['label' => __('msg_users.label_email'), 'value' => $user->email],
            ['label' => __('msg_users.label_role'), 'value' => $user->role_id?->label()],
            ['label' => __('msg_users.label_status'), 'value' => $user->status?->label()],
            ['label' => __('msg_users.label_created_at'), 'value' => $user->created_at?->format('d/m/Y H:i')],
        ];
        $this->viewTitle = $user->name;
        $this->showView = true;
    }

    public function confirmReset(int $id): void
    {
        $this->requestConfirm('resetPassword', [$id], [
            'title' => __('msg_users.reset_password_title'),
            'message' => __('msg_users.reset_password_message'),
            'label' => __('msg_users.reset_password_label'),
        ]);
    }

    public function toggleStatus(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        $user = User::query()->findOrFail($id);
        $user->update(['status' => $user->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify(__('msg_users.status_updated'));
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        User::query()->findOrFail($id)->delete();
        $this->notify(__('msg_users.user_deleted'));
    }

    public function resetPassword(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $newPassword = Str::password(12);
        $user->update(['password' => Hash::make($newPassword)]);

        $this->generatedFor = $user->name;
        $this->generatedPassword = $newPassword;
        $this->notify(__('msg_users.password_reset_success'));
    }

    public function confirmImpersonate(int $id): void
    {
        $this->requestConfirm('impersonate', [$id], [
            'title' => __('crud_users.impersonate_title'),
            'message' => __('crud_users.impersonate_message'),
            'label' => __('common.impersonate'),
        ]);
    }

    public function impersonate(int $id)
    {
        $current = Auth::user();

        abort_unless($current?->isSuperAdmin() ?? false, 403);
        abort_if($id === $current->id, 403);

        $target = User::query()->findOrFail($id);

        ImpersonationLog::query()->create([
            'impersonator_id' => $current->id,
            'impersonated_id' => $target->id,
            'action' => 'started',
            'ip' => request()->ip(),
            'user_agent' => mb_substr((string) request()->userAgent(), 0, 255),
        ]);

        session(['impersonator_id' => $current->id]);
        Auth::login($target);

        // Respeita as permissões do personificado: admin/super vão ao painel;
        // demais, ao site.
        $destination = $target->hasRole('admin', 'super-admin') ? 'admin.dashboard' : 'site.home';

        return $this->redirect(route($destination));
    }

    public function exportCsv()
    {
        $filename = 'usuarios-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nome', 'E-mail', 'Perfil', 'Status', 'Criado em']);

            User::query()->where('id', '<>', auth()->id())->with('role')->orderBy('id')
                ->chunk(200, function ($users) use ($out): void {
                    foreach ($users as $user) {
                        fputcsv($out, [
                            $user->id,
                            $user->name,
                            $user->email,
                            $user->role_id?->label(),
                            $user->status?->label(),
                            $user->created_at?->format('d/m/Y H:i'),
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render(): Factory|View
    {
        $queryBase = User::query()
            ->where('id', '<>', auth()->id());

        $users = $this->applyTable($queryBase, ['name', 'email']);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    protected function sortableColumns(): array
    {
        return ['id', 'name', 'email', 'status', 'created_at'];
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ];
    }
}
