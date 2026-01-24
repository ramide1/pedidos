<x-layouts::app :title="__('Agregar Restaurante')">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <flux:button href="{{ route('restaurantes.index') }}" variant="ghost" icon="arrow-left" size="sm" />
            <flux:heading size="xl" level="1">{{ __('Nuevo Restaurante') }}</flux:heading>
        </div>

        <flux:card>
            <form action="{{ route('restaurantes.store') }}" method="POST" class="space-y-6">
                @csrf

                @include('restaurantes.form')

                <div class="flex justify-end gap-2">
                    <flux:button href="{{ route('restaurantes.index') }}" variant="ghost">{{ __('Cancelar') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Guardar Restaurante') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>