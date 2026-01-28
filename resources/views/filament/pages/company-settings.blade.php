<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Simpan
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
