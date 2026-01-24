<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        <flux:heading size="xl" level="1">{{ __('Dashboard') }}</flux:heading>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card class="flex flex-col gap-2">
                <flux:text size="sm" class="text-zinc-500 uppercase font-bold tracking-wider">{{ __('Total Pedidos') }}</flux:text>
                <div class="flex items-center justify-between">
                    <flux:heading size="xl">{{ $totalPedidos }}</flux:heading>
                    <flux:icon name="shopping-cart" class="size-8 text-primary" />
                </div>
            </flux:card>

            <flux:card class="flex flex-col gap-2">
                <flux:text size="sm" class="text-zinc-500 uppercase font-bold tracking-wider">{{ __('Ventas Totales') }}</flux:text>
                <div class="flex items-center justify-between">
                    <flux:heading size="xl">${{ number_format($totalVentas, 2) }}</flux:heading>
                    <flux:icon name="banknotes" class="size-8 text-green-500" />
                </div>
            </flux:card>

            <flux:card class="flex flex-col gap-2">
                <flux:text size="sm" class="text-zinc-500 uppercase font-bold tracking-wider">{{ __('Productos') }}</flux:text>
                <div class="flex items-center justify-between">
                    <flux:heading size="xl">{{ $totalProductos }}</flux:heading>
                    <flux:icon name="cube" class="size-8 text-amber-500" />
                </div>
            </flux:card>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Simple Orders Chart -->
            <flux:card>
                <flux:heading level="3" class="mb-6">{{ __('Pedidos por Mes') }}</flux:heading>
                <div class="flex gap-4 h-48 pt-4">
                    @foreach($pedidosPorMes as $mes)
                    @php
                    $maxCount = max($pedidosPorMes->max('count'), 1);
                    $hPercentage = max(($mes->count / $maxCount) * 100, 5) . '%';
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <div class="w-full bg-primary/20 rounded-t-lg relative flex items-end justify-center transition-all group-hover:bg-primary/40" style="height: '{{ $hPercentage }}'">
                            <span class="absolute -top-6 text-xs font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity">{{ $mes->count }}</span>
                        </div>
                        <span class="text-[10px] text-zinc-500 font-mono">{{ $mes->month }}</span>
                    </div>
                    @endforeach

                    @if($pedidosPorMes->isEmpty())
                    <div class="flex-1 flex items-center justify-center text-zinc-400 italic">
                        {{ __('No hay datos suficientes') }}
                    </div>
                    @endif
                </div>
            </flux:card>

            <!-- Shortcuts / User Info -->
            <flux:card class="space-y-4">
                <flux:heading level="3">{{ __('Gestión Rápida') }}</flux:heading>
                <div class="grid grid-cols-2 gap-4">
                    <flux:button href="{{ route('restaurantes.create') }}" variant="ghost" class="flex flex-col items-center gap-2 py-6 h-auto">
                        <flux:icon name="plus" />
                        <span>{{ __('Nuevo Restaurante') }}</span>
                    </flux:button>
                    <flux:button href="{{ route('categorias.create') }}" variant="ghost" class="flex flex-col items-center gap-2 py-6 h-auto">
                        <flux:icon name="plus" />
                        <span>{{ __('Nueva Categoría') }}</span>
                    </flux:button>
                    <flux:button href="{{ route('productos.create') }}" variant="ghost" class="flex flex-col items-center gap-2 py-6 h-auto">
                        <flux:icon name="plus" />
                        <span>{{ __('Nuevo Producto') }}</span>
                    </flux:button>
                    <flux:button href="{{ route('pedidos.index') }}" variant="ghost" class="flex flex-col items-center gap-2 py-6 h-auto">
                        <flux:icon name="eye" />
                        <span>{{ __('Ver Pedidos') }}</span>
                    </flux:button>
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts::app>