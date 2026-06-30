<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    use HasAdminActions, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    public ?string $generatedPassword = null;

    public ?string $generatedFor = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

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

        User::query()->updateOrCreate(
            ['id' => $this->editingId],
            $this->editingId
                ? $data
                : [...$data, 'slug' => Str::slug($this->name).'-'.Str::random(5), 'password' => bcrypt(Str::password(12))]
        );

        $message = $this->editingId ? 'Usuário atualizado.' : 'Usuário criado.';
        $this->showModal = false;
        $this->notify($message);
    }

    public function view(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $this->viewData = [
            ['label' => 'Nome', 'value' => $user->name],
            ['label' => 'E-mail', 'value' => $user->email],
            ['label' => 'Perfil', 'value' => $user->role_id?->label()],
            ['label' => 'Status', 'value' => $user->status?->label()],
            ['label' => 'Criado em', 'value' => $user->created_at?->format('d/m/Y H:i')],
        ];
        $this->viewTitle = $user->name;
        $this->showView = true;
    }

    public function confirmReset(int $id): void
    {
        $this->requestConfirm('resetPassword', [$id], [
            'title' => 'Resetar senha',
            'message' => 'Gerar uma nova senha aleatória para este usuário?',
            'label' => 'Gerar senha',
        ]);
    }

    public function toggleStatus(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        $user = User::query()->findOrFail($id);
        $user->update(['status' => $user->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify('Status atualizado.');
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        User::query()->findOrFail($id)->delete();
        $this->notify('Usuário excluído.');
    }

    public function resetPassword(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $newPassword = Str::password(12);
        $user->update(['password' => Hash::make($newPassword)]);

        $this->generatedFor = $user->name;
        $this->generatedPassword = $newPassword;
        $this->notify('Senha redefinida com sucesso.');
    }

    public function exportCsv()
    {
        $filename = 'usuarios-'.now()->format('Ymd_His').'.csv';

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
        $users = User::query()
            ->where('id', '<>', auth()->id())
            ->when($this->search, fn ($q) => $q->where(
                fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
            ))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
