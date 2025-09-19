<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <x-filament::button type="submit">
                Atualizar
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
