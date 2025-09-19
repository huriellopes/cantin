@php
    use App\Enum\Role as RoleEnum;
    $user = auth()->user();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Bem vindo, :name!', ['name' => $user->name]) }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Você possui o perfil de :role.', ['role' => RoleEnum::from($user->role->id)->label()]) }}
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
