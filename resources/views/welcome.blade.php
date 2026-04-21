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
                        <span>{{ number_format(4.5 + ($restaurante->pedidos_count % 5) / 10, 1) }}</span>
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

    <footer class="pt-12 border-t border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
            <div class="text-center md:text-left">
                © {{ date('Y') }} <span class="font-medium text-zinc-700 dark:text-zinc-300">Ramiro Depaoli</span> — {{ __('Hecho con ❤️ para los amantes de la buena comida') }}
            </div>
            <div class="flex flex-wrap justify-center !gap-5">
                <a href="https://github.com/ramide1/pedidos" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors flex items-center gap-1" aria-label="GitHub Repositorio">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M237.9 461.4C237.9 463.4 235.6 465 232.7 465C229.4 465.3 227.1 463.7 227.1 461.4C227.1 459.4 229.4 457.8 232.3 457.8C235.3 457.5 237.9 459.1 237.9 461.4zM206.8 456.9C206.1 458.9 208.1 461.2 211.1 461.8C213.7 462.8 216.7 461.8 217.3 459.8C217.9 457.8 216 455.5 213 454.6C210.4 453.9 207.5 454.9 206.8 456.9zM251 455.2C248.1 455.9 246.1 457.8 246.4 460.1C246.7 462.1 249.3 463.4 252.3 462.7C255.2 462 257.2 460.1 256.9 458.1C256.6 456.2 253.9 454.9 251 455.2zM316.8 72C178.1 72 72 177.3 72 316C72 426.9 141.8 521.8 241.5 555.2C254.3 557.5 258.8 549.6 258.8 543.1C258.8 536.9 258.5 502.7 258.5 481.7C258.5 481.7 188.5 496.7 173.8 451.9C173.8 451.9 162.4 422.8 146 415.3C146 415.3 123.1 399.6 147.6 399.9C147.6 399.9 172.5 401.9 186.2 425.7C208.1 464.3 244.8 453.2 259.1 446.6C261.4 430.6 267.9 419.5 275.1 412.9C219.2 406.7 162.8 398.6 162.8 302.4C162.8 274.9 170.4 261.1 186.4 243.5C183.8 237 175.3 210.2 189 175.6C209.9 169.1 258 202.6 258 202.6C278 197 299.5 194.1 320.8 194.1C342.1 194.1 363.6 197 383.6 202.6C383.6 202.6 431.7 169 452.6 175.6C466.3 210.3 457.8 237 455.2 243.5C471.2 261.2 481 275 481 302.4C481 398.9 422.1 406.6 366.2 412.9C375.4 420.8 383.2 435.8 383.2 459.3C383.2 493 382.9 534.7 382.9 542.9C382.9 549.4 387.5 557.3 400.2 555C500.2 521.8 568 426.9 568 316C568 177.3 455.5 72 316.8 72zM169.2 416.9C167.9 417.9 168.2 420.2 169.9 422.1C171.5 423.7 173.8 424.4 175.1 423.1C176.4 422.1 176.1 419.8 174.4 417.9C172.8 416.3 170.5 415.6 169.2 416.9zM158.4 408.8C157.7 410.1 158.7 411.7 160.7 412.7C162.3 413.7 164.3 413.4 165 412C165.7 410.7 164.7 409.1 162.7 408.1C160.7 407.5 159.1 407.8 158.4 408.8zM190.8 444.4C189.2 445.7 189.8 448.7 192.1 450.6C194.4 452.9 197.3 453.2 198.6 451.6C199.9 450.3 199.3 447.3 197.3 445.4C195.1 443.1 192.1 442.8 190.8 444.4zM179.4 429.7C177.8 430.7 177.8 433.3 179.4 435.6C181 437.9 183.7 438.9 185 437.9C186.6 436.6 186.6 434 185 431.7C183.6 429.4 181 428.4 179.4 429.7z" fill="currentColor" />
                    </svg>
                    <span>Repo</span>
                </a>
                <a href="https://github.com/ramide1" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors flex items-center gap-1" aria-label="GitHub Perfil">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M237.9 461.4C237.9 463.4 235.6 465 232.7 465C229.4 465.3 227.1 463.7 227.1 461.4C227.1 459.4 229.4 457.8 232.3 457.8C235.3 457.5 237.9 459.1 237.9 461.4zM206.8 456.9C206.1 458.9 208.1 461.2 211.1 461.8C213.7 462.8 216.7 461.8 217.3 459.8C217.9 457.8 216 455.5 213 454.6C210.4 453.9 207.5 454.9 206.8 456.9zM251 455.2C248.1 455.9 246.1 457.8 246.4 460.1C246.7 462.1 249.3 463.4 252.3 462.7C255.2 462 257.2 460.1 256.9 458.1C256.6 456.2 253.9 454.9 251 455.2zM316.8 72C178.1 72 72 177.3 72 316C72 426.9 141.8 521.8 241.5 555.2C254.3 557.5 258.8 549.6 258.8 543.1C258.8 536.9 258.5 502.7 258.5 481.7C258.5 481.7 188.5 496.7 173.8 451.9C173.8 451.9 162.4 422.8 146 415.3C146 415.3 123.1 399.6 147.6 399.9C147.6 399.9 172.5 401.9 186.2 425.7C208.1 464.3 244.8 453.2 259.1 446.6C261.4 430.6 267.9 419.5 275.1 412.9C219.2 406.7 162.8 398.6 162.8 302.4C162.8 274.9 170.4 261.1 186.4 243.5C183.8 237 175.3 210.2 189 175.6C209.9 169.1 258 202.6 258 202.6C278 197 299.5 194.1 320.8 194.1C342.1 194.1 363.6 197 383.6 202.6C383.6 202.6 431.7 169 452.6 175.6C466.3 210.3 457.8 237 455.2 243.5C471.2 261.2 481 275 481 302.4C481 398.9 422.1 406.6 366.2 412.9C375.4 420.8 383.2 435.8 383.2 459.3C383.2 493 382.9 534.7 382.9 542.9C382.9 549.4 387.5 557.3 400.2 555C500.2 521.8 568 426.9 568 316C568 177.3 455.5 72 316.8 72zM169.2 416.9C167.9 417.9 168.2 420.2 169.9 422.1C171.5 423.7 173.8 424.4 175.1 423.1C176.4 422.1 176.1 419.8 174.4 417.9C172.8 416.3 170.5 415.6 169.2 416.9zM158.4 408.8C157.7 410.1 158.7 411.7 160.7 412.7C162.3 413.7 164.3 413.4 165 412C165.7 410.7 164.7 409.1 162.7 408.1C160.7 407.5 159.1 407.8 158.4 408.8zM190.8 444.4C189.2 445.7 189.8 448.7 192.1 450.6C194.4 452.9 197.3 453.2 198.6 451.6C199.9 450.3 199.3 447.3 197.3 445.4C195.1 443.1 192.1 442.8 190.8 444.4zM179.4 429.7C177.8 430.7 177.8 433.3 179.4 435.6C181 437.9 183.7 438.9 185 437.9C186.6 436.6 186.6 434 185 431.7C183.6 429.4 181 428.4 179.4 429.7z" fill="currentColor" />
                    </svg>
                    <span>GitHub</span>
                </a>
                <a href="https://www.linkedin.com/in/ramiro-depaoli/?locale=es" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors flex items-center gap-1" aria-label="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M512 96L127.9 96C110.3 96 96 110.5 96 128.3L96 511.7C96 529.5 110.3 544 127.9 544L512 544C529.6 544 544 529.5 544 511.7L544 128.3C544 110.5 529.6 96 512 96zM231.4 480L165 480L165 266.2L231.5 266.2L231.5 480L231.4 480zM198.2 160C219.5 160 236.7 177.2 236.7 198.5C236.7 219.8 219.5 237 198.2 237C176.9 237 159.7 219.8 159.7 198.5C159.7 177.2 176.9 160 198.2 160zM480.3 480L413.9 480L413.9 376C413.9 351.2 413.4 319.3 379.4 319.3C344.8 319.3 339.5 346.3 339.5 374.2L339.5 480L273.1 480L273.1 266.2L336.8 266.2L336.8 295.4L337.7 295.4C346.6 278.6 368.3 260.9 400.6 260.9C467.8 260.9 480.3 305.2 480.3 362.8L480.3 480z" fill="currentColor" />
                    </svg>
                    <span>LinkedIn</span>
                </a>
                <a href="https://ramide1.github.io/portfolio/" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors flex items-center gap-1" aria-label="Portafolio">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M415.9 344L225 344C227.9 408.5 242.2 467.9 262.5 511.4C273.9 535.9 286.2 553.2 297.6 563.8C308.8 574.3 316.5 576 320.5 576C324.5 576 332.2 574.3 343.4 563.8C354.8 553.2 367.1 535.8 378.5 511.4C398.8 467.9 413.1 408.5 416 344zM224.9 296L415.8 296C413 231.5 398.7 172.1 378.4 128.6C367 104.2 354.7 86.8 343.3 76.2C332.1 65.7 324.4 64 320.4 64C316.4 64 308.7 65.7 297.5 76.2C286.1 86.8 273.8 104.2 262.4 128.6C242.1 172.1 227.8 231.5 224.9 296zM176.9 296C180.4 210.4 202.5 130.9 234.8 78.7C142.7 111.3 74.9 195.2 65.5 296L176.9 296zM65.5 344C74.9 444.8 142.7 528.7 234.8 561.3C202.5 509.1 180.4 429.6 176.9 344L65.5 344zM463.9 344C460.4 429.6 438.3 509.1 406 561.3C498.1 528.6 565.9 444.8 575.3 344L463.9 344zM575.3 296C565.9 195.2 498.1 111.3 406 78.7C438.3 130.9 460.4 210.4 463.9 296L575.3 296z" fill="currentColor" />
                    </svg>
                    <span>Portfolio</span>
                </a>
                <a href="mailto:ramiro.depaoli@gmail.com" class="hover:text-primary transition-colors flex items-center gap-1" aria-label="Correo electrónico">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M112 128C85.5 128 64 149.5 64 176C64 191.1 71.1 205.3 83.2 214.4L291.2 370.4C308.3 383.2 331.7 383.2 348.8 370.4L556.8 214.4C568.9 205.3 576 191.1 576 176C576 149.5 554.5 128 528 128L112 128zM64 260L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 260L377.6 408.8C343.5 434.4 296.5 434.4 262.4 408.8L64 260z" fill="currentColor" />
                    </svg>
                    <span>Mail</span>
                </a>
            </div>
        </div>
    </footer>
</x-layouts::app>