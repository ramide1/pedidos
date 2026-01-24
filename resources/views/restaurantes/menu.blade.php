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

                    <form action="{{ route('public.pedidos.store') }}" method="POST" id="order-form" class="space-y-4">
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
                    </form>
                </flux:card>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        const renderCart = () => {
            const container = document.getElementById('cart-items');
            const totalEl = document.getElementById('cart-total');
            const emptyMsg = document.getElementById('empty-cart-msg');
            const submitBtn = document.getElementById('submit-btn');

            if (!container) return;

            container.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                container.appendChild(emptyMsg);
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
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

        const addItem = (id, nombre, precio) => {
            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.cantidad++;
            } else {
                cart.push({
                    id,
                    nombre,
                    precio,
                    cantidad: 1
                });
            }
            renderCart();
        }

        const subtractItem = (id) => {
            const index = cart.findIndex(i => i.id === id);
            if (index > -1) {
                cart[index].cantidad--;
                if (cart[index].cantidad <= 0) {
                    cart.splice(index, 1);
                }
            }
            renderCart();
        }
    </script>
</x-layouts::app>