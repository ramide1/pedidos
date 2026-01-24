<x-layouts::app :title="__('Editar Pedido')">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <flux:button href="{{ route('pedidos.index') }}" variant="ghost" icon="arrow-left" size="sm" />
            <flux:heading size="xl" level="1">{{ __('Pedido') }} #{{ $pedido->codigo }}</flux:heading>
        </div>

        <flux:card>
            <form action="{{ route('pedidos.update', $pedido) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <flux:field>
                    <flux:label>{{ __('Estado del Pedido') }}</flux:label>
                    <flux:select name="estado">
                        @foreach(['pendiente', 'confirmado', 'en_preparacion', 'en_camino', 'entregado', 'cancelado'] as $estado)
                        <option value="{{ $estado }}" {{ old('estado', $pedido->estado) == $estado ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                        </option>
                        @endforeach
                    </flux:select>
                    <flux:error name="estado" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Pago Confirmado') }}</flux:label>
                    <flux:select name="pago_confirmado">
                        <option value="1" {{ old('pago_confirmado', $pedido->pago_confirmado) == 1 ? 'selected' : '' }}>{{ __('Sí') }}</option>
                        <option value="0" {{ old('pago_confirmado', $pedido->pago_confirmado) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Notas internas') }}</flux:label>
                    <flux:textarea name="notas">{{ old('notas', $pedido->notas) }}</flux:textarea>
                    <flux:error name="notas" />
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:button href="{{ route('pedidos.index') }}" variant="ghost">{{ __('Cancelar') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Actualizar Pedido') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>