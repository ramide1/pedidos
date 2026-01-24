<x-layouts::app :title="__('Explora Restaurantes')">
    <div class="space-y-12">
        <!-- Hero Section -->
        <div class="relative bg-primary/10 rounded-3xl p-8 md:p-16 overflow-hidden">
            <div class="relative z-10 max-w-2xl space-y-6">
                <flux:heading size="xl" level="1" class="text-4xl md:text-6xl font-black leading-tight">
                    {{ __('Tus restaurantes favoritos,') }} <br>
                    <span class="text-primary">{{ __('a un click de distancia.') }}</span>
                </flux:heading>
                <flux:text size="lg" class="text-zinc-600 dark:text-zinc-400">
                    {{ __('Pide comida a domicilio de los mejores lugares de la ciudad de forma rápida y sencilla.') }}
                </flux:text>

                <!-- Search Box -->
                <form action="{{ route('home') }}" method="GET" class="flex gap-2 max-w-lg">
                    <flux:input
                        name="search"
                        placeholder="{{ __('Buscar por nombre, tipo de cocina...') }}"
                        value="{{ request('search') }}"
                        class="flex-1"
                        icon="magnifying-glass" />
                    <flux:button type="submit" variant="primary">{{ __('Buscar') }}</flux:button>
                </form>
            </div>

            <!-- Decorative burger (using our component) -->
            <div class="absolute -right-20 -bottom-20 opacity-10 rotate-12 hidden lg:block">
                <x-app-logo-icon class="size-96" />
            </div>
        </div>

        <!-- Restaurants Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <flux:heading size="xl" level="2">
                    @if(request('search'))
                    {{ __('Resultados para') }} "{{ request('search') }}"
                    @else
                    {{ __('Los más pedidos') }}
                    @endif
                </flux:heading>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($restaurantes as $restaurante)
                <flux:card class="p-0 overflow-hidden group hover:shadow-2xl transition-all duration-300 border-zinc-200 dark:border-zinc-800">
                    <a href="{{ route('restaurantes.menu', $restaurante) }}" class="block">
                        <div class="aspect-video bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center overflow-hidden relative">
                            @if($restaurante->imagen)
                            <img src="{{ $restaurante->imagen }}" alt="{{ $restaurante->nombre }}" class="object-cover size-full group-hover:scale-105 transition-transform duration-500">
                            @else
                            <flux:icon name="building-storefront" class="size-16 text-zinc-300 transition-colors group-hover:text-primary" />
                            @endif

                            <!-- Badge for popular -->
                            @if($loop->index < 3 && !request('search'))
                                <div class="absolute top-3 left-3">
                                <flux:badge color="amber" icon="fire">{{ __('Popular') }}</flux:badge>
                        </div>
                        @endif
            </div>

            <div class="p-5 space-y-3">
                <div class="flex justify-between items-start gap-2">
                    <flux:heading size="lg" class="group-hover:text-primary transition-colors line-clamp-1">{{ $restaurante->nombre }}</flux:heading>
                </div>

                <div class="flex flex-col gap-1 text-sm text-zinc-500">
                    <span class="flex items-center gap-1">
                        <flux:icon name="tag" size="sm" class="shrink-0" /> {{ ucfirst($restaurante->tipo_cocina) }}
                    </span>
                    <span class="flex items-center gap-1 line-clamp-1">
                        <flux:icon name="map-pin" size="sm" class="shrink-0" /> {{ $restaurante->direccion }}
                    </span>
                </div>

                <flux:separator />

                <div class="flex items-center justify-between pt-1">
                    <flux:text size="sm" class="flex items-center gap-1 font-medium text-zinc-900 dark:text-white">
                        <flux:icon name="star" variant="solid" class="size-4 text-amber-400" />
                        <span>{{ number_format(4.5 + ($restaurante->pedidos_count % 5) / 10, 1) }}</span> <!-- Fake rating based on IDs data -->
                        <span class="text-zinc-400 font-normal">({{ $restaurante->pedidos_count }} {{ __('pedidos') }})</span>
                    </flux:text>
                    <flux:button variant="primary" size="sm">{{ __('Pedir ahora') }}</flux:button>
                </div>
            </div>
            </a>
            </flux:card>
            @endforeach
        </div>

        @if($restaurantes->isEmpty())
        <div class="flex flex-col items-center justify-center p-20 text-center space-y-4">
            <flux:icon name="magnifying-glass" class="size-20 text-zinc-200" />
            <flux:heading level="3">{{ __('No encontramos lo que buscas') }}</flux:heading>
            <flux:text>{{ __('Prueba con otros términos o explora libremente.') }}</flux:text>
            <flux:button href="{{ route('home') }}" variant="ghost">{{ __('Ver todos los restaurantes') }}</flux:button>
        </div>
        @endif
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-12">
        <div class="flex flex-col items-center text-center space-y-4 p-6">
            <div class="size-16 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-full flex items-center justify-center">
                <flux:icon name="rocket-launch" size="xl" />
            </div>
            <flux:heading level="4" size="lg">{{ __('Entrega Rápida') }}</flux:heading>
            <flux:text>{{ __('Recibe tu comida en el menor tiempo posible.') }}</flux:text>
        </div>
        <div class="flex flex-col items-center text-center space-y-4 p-6">
            <div class="size-16 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full flex items-center justify-center">
                <flux:icon name="currency-dollar" size="xl" />
            </div>
            <flux:heading level="4" size="lg">{{ __('Mejores Precios') }}</flux:heading>
            <flux:text>{{ __('Las mejores ofertas y descuentos exclusivos.') }}</flux:text>
        </div>
        <div class="flex flex-col items-center text-center space-y-4 p-6">
            <div class="size-16 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-full flex items-center justify-center">
                <flux:icon name="check-badge" size="xl" />
            </div>
            <flux:heading level="4" size="lg">{{ __('Calidad Garantizada') }}</flux:heading>
            <flux:text>{{ __('Solo trabajamos con los mejores restaurantes.') }}</flux:text>
        </div>
    </div>
    </div>
</x-layouts::app>