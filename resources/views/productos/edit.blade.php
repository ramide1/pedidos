<x-layouts::app :title="__('Editar Producto')">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <flux:button href="{{ route('productos.index') }}" variant="ghost" icon="arrow-left" size="sm" />
            <flux:heading size="xl" level="1">{{ __('Editar Producto') }}</flux:heading>
        </div>

        <flux:card>
            <form action="{{ route('productos.update', $producto) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                @include('productos.form')

                <div class="flex justify-end gap-2">
                    <flux:button href="{{ route('productos.index') }}" variant="ghost">{{ __('Cancelar') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Actualizar Producto') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>