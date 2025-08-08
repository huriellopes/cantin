<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Bem vindo, :name!', ['name' => Auth::user()->name]) }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Você está logado como :email.', ['email' => Auth::user()->email]) }}
        </p>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Você possui permissão de :role.', ['role' => Auth::user()->role->name]) }}
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
