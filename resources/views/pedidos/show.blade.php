<x-layouts::app :title="__('Detalles del Pedido')">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <flux:button href="{{ route('pedidos.index') }}" variant="ghost" icon="arrow-left" size="sm" />
                <flux:heading size="xl" level="1">{{ __('Pedido') }} #{{ $pedido->codigo }}</flux:heading>
            </div>
            <flux:button href="{{ route('pedidos.edit', $pedido) }}" icon="pencil-square">{{ __('Editar') }}</flux:button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <flux:card>
                    <flux:heading level="3" class="mb-4">{{ __('Productos') }}</flux:heading>
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>{{ __('Producto') }}</flux:table.column>
                            <flux:table.column>{{ __('Cant.') }}</flux:table.column>
                            <flux:table.column>{{ __('Precio') }}</flux:table.column>
                            <flux:table.column class="text-right">{{ __('Subtotal') }}</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @php
                            $subtotal = 0;
                            @endphp
                            @foreach($pedido->items as $item)
                            <flux:table.row>
                                <flux:table.cell>{{ $item->producto->nombre ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>{{ $item->cantidad }}</flux:table.cell>
                                <flux:table.cell>${{ number_format($item->precio, 2) }}</flux:table.cell>
                                <flux:table.cell class="text-right font-medium">${{ number_format(($item->cantidad * $item->precio), 2) }}</flux:table.cell>
                            </flux:table.row>
                            @php
                            $subtotal += number_format(($item->cantidad * $item->precio), 2);
                            @endphp
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            </div>

            <div class="space-y-6">
                <flux:card>
                    <flux:heading level="3" class="mb-4">{{ __('Resumen') }}</flux:heading>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">{{ __('Subtotal') }}</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">{{ __('Envío') }}</span>
                            <span>${{ number_format($pedido->costo_envio, 2) }}</span>
                        </div>
                        <flux:separator />
                        <div class="flex justify-between font-bold text-lg">
                            <span>{{ __('Total') }}</span>
                            <span>${{ number_format($pedido->total, 2) }}</span>
                        </div>
                    </div>
                </flux:card>

                <flux:card>
                    <flux:heading level="3" class="mb-4">{{ __('Información del Cliente') }}</flux:heading>
                    <div class="space-y-3">
                        <div>
                            <flux:label class="text-xs uppercase text-zinc-400">{{ __('Nombre') }}</flux:label>
                            <div class="text-sm font-medium">{{ $pedido->nombre }}</div>
                        </div>
                        <div>
                            <flux:label class="text-xs uppercase text-zinc-400">{{ __('Email') }}</flux:label>
                            <div class="text-sm">{{ $pedido->email }}</div>
                        </div>
                        <div>
                            <flux:label class="text-xs uppercase text-zinc-400">{{ __('Teléfono') }}</flux:label>
                            <div class="text-sm">{{ $pedido->telefono }}</div>
                        </div>
                        <div>
                            <flux:label class="text-xs uppercase text-zinc-400">{{ __('Dirección') }}</flux:label>
                            <div class="text-sm">{{ $pedido->direccion }}</div>
                        </div>
                    </div>
                </flux:card>
            </div>
        </div>
    </div>
</x-layouts::app>