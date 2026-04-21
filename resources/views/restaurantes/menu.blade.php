<x-layouts::app :title="$restaurante->nombre">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Restaurant Header -->
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <div class="size-32 bg-zinc-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden border">
                @if($restaurante->imagen)
                <img src="{{ $restaurante->imagen }}" alt="{{ $restaurante->nombre }}" class="object-cover size-full">
                @else
                <flux:icon name="building-storefront" class="size-12 text-zinc-300" />
                @endif
            </div>
            <div class="space-y-2">
                <flux:heading size="xl">{{ $restaurante->nombre }}</flux:heading>
                <div class="flex flex-wrap gap-4 text-sm text-zinc-500">
                    <span class="flex items-center gap-1">
                        <flux:icon name="map-pin" size="sm" /> {{ $restaurante->direccion }}
                    </span>
                    <span class="flex items-center gap-1">
                        <flux:icon name="phone" size="sm" /> {{ $restaurante->telefono }}
                    </span>
                    <span class="flex items-center gap-1">
                        <flux:icon name="tag" size="sm" /> {{ ucfirst($restaurante->tipo_cocina) }}
                    </span>
                </div>
                @if($restaurante->notas)
                <flux:text class="italic">{{ $restaurante->notas }}</flux:text>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Menu Section -->
            <div class="lg:col-span-2 space-y-10">
                @foreach($restaurante->categorias as $categoria)
                <section class="space-y-4">
                    <flux:heading level="2" size="lg" class="border-b pb-2">{{ $categoria->nombre }}</flux:heading>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($categoria->productos as $producto)
                        <flux:card class="p-4 flex justify-between items-center group hover:border-primary transition-colors">
                            <div class="space-y-1">
                                <flux:heading size="md">{{ $producto->nombre }}</flux:heading>
                                @if($producto->notas)
                                <flux:text size="sm">{{ $producto->notas }}</flux:text>
                                @endif
                                <flux:heading size="sm" class="text-primary font-bold">${{ number_format($producto->precio, 2) }}</flux:heading>
                            </div>
                            <flux:button icon="plus" size="sm" variant="ghost" class="group-hover:bg-primary/10" onclick="addItem('{{ $producto->id }}', '{{ $producto->nombre }}', '{{ $producto->precio }}')" />
                        </flux:card>
                        @endforeach
                    </div>
                </section>
                @endforeach

                @if($restaurante->categorias->isEmpty())
                <section class="space-y-4">
                    <flux:heading level="2" size="lg" class="border-b pb-2">{{ __('Menu') }}</flux:heading>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($restaurante->productos as $producto)
                        <flux:card class="p-4 flex justify-between items-center group hover:border-primary transition-colors">
                            <div class="space-y-1">
                                <flux:heading size="md">{{ $producto->nombre }}</flux:heading>
                                @if($producto->notas)
                                <flux:text size="sm">{{ $producto->notas }}</flux:text>
                                @endif
                                <flux:heading size="sm" class="text-primary font-bold">${{ number_format($producto->precio, 2) }}</flux:heading>
                            </div>
                            <flux:button icon="plus" size="sm" variant="ghost" class="group-hover:bg-primary/10" onclick="addItem('{{ $producto->id }}', '{{ $producto->nombre }}', '{{ $producto->precio }}')" />
                        </flux:card>
                        @endforeach
                    </div>
                </section>
                @endif
                @if($restaurante->productos->isEmpty())
                <div class="flex flex-col items-center justify-center p-12 text-center border-2 border-dashed rounded-xl">
                    <flux:icon name="rectangle-stack" class="size-12 text-zinc-300 mb-4" />
                    <flux:text>{{ __('Este restaurante aún no tiene productos en su menú.') }}</flux:text>
                </div>
                @endif
            </div>

            <!-- Order / Checkout Section -->
            <div class="space-y-6">
                <flux:card class="sticky top-6">
                    <flux:heading level="3" class="mb-4 flex items-center gap-2">
                        <flux:icon name="shopping-cart" />
                        {{ __('Tu Pedido') }}
                    </flux:heading>

                    <form action="{{ route('pedidos.store') }}" method="POST" id="order-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="restaurante_id" value="{{ $restaurante->id }}">

                        <!-- Selected Items Container -->
                        <div id="cart-items" class="space-y-3 min-h-12 border-b pb-4 mb-4">
                            <flux:text size="sm" class="text-zinc-400 italic text-center py-4" id="empty-cart-msg">
                                {{ __('No hay productos seleccionados') }}
                            </flux:text>
                        </div>

                        <!-- User Info (Auth or Guest) -->
                        <div class="space-y-4 pt-2">
                            <flux:field>
                                <flux:label>{{ __('Tu Nombre') }}</flux:label>
                                <flux:input name="nombre" value="{{ auth()->user()?->name ?? '' }}" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Email') }}</flux:label>
                                <flux:input type="email" name="email" value="{{ auth()->user()?->email ?? '' }}" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Teléfono') }}</flux:label>
                                <flux:input name="telefono" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Dirección de Entrega') }}</flux:label>
                                <flux:input name="direccion" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Notas para el repartidor') }}</flux:label>
                                <flux:textarea name="notas" rows="2" />
                            </flux:field>
                        </div>

                        <div class="pt-4 space-y-2 border-t font-bold">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('Total') }}</span>
                                <span class="text-xl text-primary" id="cart-total">$0.00</span>
                            </div>
                        </div>

                        <flux:button type="submit" variant="primary" class="w-full" id="submit-btn" disabled>
                            {{ __('Realizar Pedido') }}
                        </flux:button>

                        <flux:button variant="primary" class="w-full" id="wp-btn" style="--color-accent: #25D366; --color-accent-foreground: white; white-space: normal; min-height: 2.5rem;" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path d="M476.9 161.1C435 119.1 379.2 96 319.9 96C197.5 96 97.9 195.6 97.9 318C97.9 357.1 108.1 395.3 127.5 429L96 544L213.7 513.1C246.1 530.8 282.6 540.1 319.8 540.1L319.9 540.1C442.2 540.1 544 440.5 544 318.1C544 258.8 518.8 203.1 476.9 161.1zM319.9 502.7C286.7 502.7 254.2 493.8 225.9 477L219.2 473L149.4 491.3L168 423.2L163.6 416.2C145.1 386.8 135.4 352.9 135.4 318C135.4 216.3 218.2 133.5 320 133.5C369.3 133.5 415.6 152.7 450.4 187.6C485.2 222.5 506.6 268.8 506.5 318.1C506.5 419.9 421.6 502.7 319.9 502.7zM421.1 364.5C415.6 361.7 388.3 348.3 383.2 346.5C378.1 344.6 374.4 343.7 370.7 349.3C367 354.9 356.4 367.3 353.1 371.1C349.9 374.8 346.6 375.3 341.1 372.5C308.5 356.2 287.1 343.4 265.6 306.5C259.9 296.7 271.3 297.4 281.9 276.2C283.7 272.5 282.8 269.3 281.4 266.5C280 263.7 268.9 236.4 264.3 225.3C259.8 214.5 255.2 216 251.8 215.8C248.6 215.6 244.9 215.6 241.2 215.6C237.5 215.6 231.5 217 226.4 222.5C221.3 228.1 207 241.5 207 268.8C207 296.1 226.9 322.5 229.6 326.2C232.4 329.9 268.7 385.9 324.4 410C359.6 425.2 373.4 426.5 391 423.9C401.7 422.3 423.8 410.5 428.4 397.5C433 384.5 433 373.4 431.6 371.1C430.3 368.6 426.6 367.2 421.1 364.5z" fill="currentColor" />
                            </svg>
                            {{ __('Realizar Pedido por Whatsapp') }}
                        </flux:button>
                    </form>
                </flux:card>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        const container = document.getElementById('cart-items');
        const totalEl = document.getElementById('cart-total');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const submitBtn = document.getElementById('submit-btn');
        const wpBtn = document.getElementById('wp-btn');

        const renderCart = () => {

            if (!container) return;

            container.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                container.appendChild(emptyMsg);
                submitBtn.disabled = true;
                wpBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
                wpBtn.disabled = false;
                cart.forEach(item => {
                    const itemTotal = item.precio * item.cantidad;
                    total += itemTotal;

                    const div = document.createElement('div');
                    div.className = 'flex justify-between items-center text-sm';
                    const safeName = item.nombre.replace(/'/g, "\\'");
                    div.innerHTML = `
                            <div class="flex flex-col">
                                <span class="font-medium">${item.nombre}</span>
                                <span class="text-xs text-zinc-400">x${item.cantidad}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>$${itemTotal.toFixed(2)}</span>
                                <div class="flex gap-1">
                                    <button type="button" class="p-1 hover:bg-zinc-100 rounded" onclick="subtractItem('${item.id}')">-</button>
                                    <button type="button" class="p-1 hover:bg-zinc-100 rounded" onclick="addItem('${item.id}', '${safeName}', '${item.precio}')">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="items[${item.id}][producto_id]" value="${item.id}">
                            <input type="hidden" name="items[${item.id}][cantidad]" value="${item.cantidad}">
                        `;
                    container.appendChild(div);
                });
            }

            totalEl.innerText = `$${total.toFixed(2)}`;
        }

        const generateWhatsAppMessage = () => {
            if (cart.length === 0) return '';

            const nombreCliente = document.querySelector('input[name="nombre"]')?.value || '';
            const emailCliente = document.querySelector('input[name="email"]')?.value || '';
            const telefonoCliente = document.querySelector('input[name="telefono"]')?.value || '';
            const direccionCliente = document.querySelector('input[name="direccion"]')?.value || '';
            const notasCliente = document.querySelector('textarea[name="notas"]')?.value || '';

            let message = '*NUEVO PEDIDO* %0A%0A';

            if (nombreCliente) message += `*Nombre:* ${nombreCliente}%0A`;
            if (emailCliente) message += `*Email:* ${emailCliente}%0A`;
            if (telefonoCliente) message += `*Teléfono:* ${telefonoCliente}%0A`;
            if (direccionCliente) message += `*Dirección:* ${direccionCliente}%0A`;
            if (notasCliente) message += `*Notas:* ${notasCliente}%0A`;

            message += '%0A*DETALLES DEL PEDIDO*%0A';

            let total = 0;

            cart.forEach((item) => {
                const itemTotal = item.precio * item.cantidad;
                total += itemTotal;

                message += `${item.nombre}%0A`;
                message += `   Cantidad: ${item.cantidad}%0A`;
                message += `   Precio unitario: $${item.precio.toFixed(2)}%0A`;
                message += `   Subtotal: $${itemTotal.toFixed(2)}%0A%0A`;
            });

            message += `*TOTAL DEL PEDIDO: $${total.toFixed(2)}*%0A%0A`;
            message += `*Fecha:* ${new Date().toLocaleString()}%0A`;
            message += `*Pedido generado automaticamente desde ${window.location.hostname} . Software web de gestión de pedidos.*`;

            return message;
        }

        const sendToWhatsApp = () => {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'El carrito está vacío',
                    icon: 'error',
                    timer: 5000,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
                return;
            }

            const message = generateWhatsAppMessage();
            const whatsappUrl = `https://wa.me/{{ $restaurante->telefono }}?text=${message}`;

            window.open(whatsappUrl, '_blank');
        }

        const addItem = (id, nombre, precio) => {
            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.cantidad++;
            } else {
                cart.push({
                    id,
                    nombre,
                    precio: parseFloat(precio),
                    cantidad: 1
                });
            }
            renderCart();
        }

        const subtractItem = (id) => {
            const index = cart.findIndex(i => i.id === id);
            if (index > -1) {
                cart[index].cantidad--;
                if (cart[index].cantidad <= 0) cart.splice(index, 1);
            }
            renderCart();
        }

        document.addEventListener('livewire:navigated', () => {
            if (wpBtn) {
                wpBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    sendToWhatsApp();
                });
            }
        }, {
            once: true
        });
    </script>
</x-layouts::app>