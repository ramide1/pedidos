<x-layouts::app :title="__('Editar Categoría')">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <flux:button href="{{ route('categorias.index') }}" variant="ghost" icon="arrow-left" size="sm" />
            <flux:heading size="xl" level="1">{{ __('Editar Categoría') }}</flux:heading>
        </div>

        <flux:card>
            <form action="{{ route('categorias.update', $categoria) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                @include('categorias.form')

                <div class="flex justify-end gap-2">
                    <flux:button href="{{ route('categorias.index') }}" variant="ghost">{{ __('Cancelar') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Actualizar Categoría') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>