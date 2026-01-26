<x-layouts::app :title="__('Pedidos')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">{{ __('Pedidos') }}</flux:heading>
        </div>

        <flux:card class="p-0 overflow-hidden">
            <div id="pedidos-table" class="w-full text-sm" data-table-data='{!! json_encode($pedidos->toArray()["data"]) !!}'></div>
            {{ $pedidos->links() }}
        </flux:card>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const divTable = document.getElementById('pedidos-table');
            if (!divTable) return;
            const tableData = divTable.dataset.tableData;
            const table = new Tabulator(divTable, {
                data: tableData,
                placeholder: `
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <flux:icon name="shopping-cart" class="size-12 text-zinc-400 mb-4" />
                    <flux:heading level="3">{{ __('No hay pedidos') }}</flux:heading>
                    <flux:text>{{ __('Los pedidos aparecerán aquí una vez que los clientes comiencen a comprar.') }}</flux:text>
                </div>
                `,
                layout: 'fitColumns',
                paginationSize: 15,
                initialSort: [{
                    column: "created_at",
                    dir: "desc"
                }],
                columns: [{
                        title: "{{ __('Código') }}",
                        field: "codigo",
                        sorter: "string",
                        minWidth: 100
                    },
                    {
                        title: "{{ __('Cliente') }}",
                        field: "nombre",
                        sorter: "string",
                        minWidth: 150,
                        formatter: (cell) => {
                            const row = cell.getRow().getData();
                            return `<div class="flex flex-col"><span class="font-medium">${row.nombre}</span><span class="text-xs text-zinc-500">${row.email || ''}</span></div>`;
                        }
                    },
                    {
                        title: "{{ __('Restaurante') }}",
                        field: "restaurante.nombre",
                        sorter: "string",
                        minWidth: 150
                    },
                    {
                        title: "{{ __('Total') }}",
                        field: "total",
                        hozAlign: "right",
                        formatter: "money",
                        formatterParams: {
                            decimal: ",",
                            thousands: ".",
                            symbol: "$ ",
                        }
                    },
                    {
                        title: "{{ __('Estado') }}",
                        field: "estado",
                        hozAlign: "center",
                        formatter: (cell) => {
                            const estado = cell.getValue();
                            const colors = {
                                'pendiente': 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400',
                                'confirmado': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'en_preparacion': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                'en_camino': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                'entregado': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'cancelado': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                            };
                            const colorClass = colors[estado] || colors.pendiente;
                            const text = estado.replace('_', ' ').charAt(0).toUpperCase() + estado.replace('_', ' ').slice(1);
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${colorClass}">${text}</span>`;
                        }
                    },
                    {
                        title: "{{ __('Pagado') }}",
                        field: "pago_confirmado",
                        hozAlign: "center",
                        formatter: "tickCross",
                        formatterParams: {
                            tickElement: `<span class="text-green-500">✓</span>`,
                            crossElement: `<span class="text-red-500">✗</span>`
                        }
                    },
                    {
                        title: "{{ __('Fecha') }}",
                        field: "created_at",
                        sorter: "datetime",
                        formatter: (cell) => {
                            const date = new Date(cell.getValue());
                            return date.toLocaleString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                    },
                    {
                        title: "{{ __('Acciones') }}",
                        field: "id",
                        headerSort: false,
                        hozAlign: "right",
                        formatter: (cell) => {
                            const id = cell.getValue();
                            const showUrl = `{{ route('pedidos.show', ':id') }}`.replace(':id', id);
                            const editUrl = `{{ route('pedidos.edit', ':id') }}`.replace(':id', id);
                            const destroyUrl = `{{ route('pedidos.destroy', ':id') }}`.replace(':id', id);
                            const markAsPaidUrl = `{{ route('pedidos.markAsPaid', ':id') }}`.replace(':id', id);
                            const csrf = `{{ csrf_token() }}`;

                            return `
                        <div class="flex items-center justify-end gap-2 pr-4">
                            <a href="${showUrl}" title="Ver" class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-zinc-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                            <a href="${editUrl}" title="Editar" class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-zinc-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>

                            <form action="${destroyUrl}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${csrf}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" title="Eliminar" class="p-1 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" onclick="confirmDelete(this.closest('form'))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>

                            <form action="${markAsPaidUrl}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${csrf}">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="button" title="Marcar como pagado" class="p-1 hover:bg-green-50 dark:hover:bg-green-900/20 rounded" onclick="confirmMarkAsPaid(this.closest('form'))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        `;
                        }
                    },
                ],
                movableColumns: true,
                movableRows: true
            });
        });
    </script>
</x-layouts::app>