<x-layouts::app :title="__('Categorías de Productos')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">{{ __('Categorías') }}</flux:heading>
            <flux:button icon="plus" variant="primary" href="{{ route('categorias.create') }}">{{ __('Agregar Categoría') }}</flux:button>
        </div>

        <flux:card class="p-0 overflow-hidden">
            <div id="categorias-table" class="w-full" data-table-data='{!! json_encode($categorias->toArray()["data"]) !!}'></div>
            {{ $categorias->links() }}
        </flux:card>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const divTable = document.getElementById('categorias-table');
            if (!divTable) return;
            const tableData = divTable.dataset.tableData;
            const table = new Tabulator(divTable, {
                data: tableData,
                placeholder: `
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <flux:icon name="rectangle-stack" class="size-12 text-zinc-400 mb-4" />
                    <flux:heading level="3">{{ __('No hay categorías') }}</flux:heading>
                    <flux:text>{{ __('Comienza agregando tu primera categoría de productos.') }}</flux:text>
                </div>
                `,
                layout: 'fitColumns',
                columns: [{
                        title: "{{ __('Restaurante') }}",
                        field: "restaurante.nombre",
                        sorter: "string",
                        minWidth: 200
                    },
                    {
                        title: "{{ __('Nombre') }}",
                        field: "nombre",
                        sorter: "string",
                        minWidth: 200
                    },
                    {
                        title: "{{ __('Estado') }}",
                        field: "activo",
                        hozAlign: "center",
                        formatter: (cell) => {
                            const isActive = cell.getValue();
                            const color = isActive ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                            const text = isActive ? "{{ __('Activo') }}" : "{{ __('Inactivo') }}";
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${color}">${text}</span>`;
                        }
                    },
                    {
                        title: "{{ __('Acciones') }}",
                        field: "id",
                        headerSort: false,
                        hozAlign: "right",
                        formatter: (cell) => {
                            const id = cell.getValue();
                            const editUrl = `{{ route('categorias.edit', ':id') }}`.replace(':id', id);
                            const duplicateUrl = `{{ route('categorias.duplicate', ':id') }}`.replace(':id', id);
                            const destroyUrl = `{{ route('categorias.destroy', ':id') }}`.replace(':id', id);
                            const csrf = `{{ csrf_token() }}`;

                            return `
                        <div class="flex items-center justify-end gap-2 pr-4">
                            <a href="${editUrl}" title="Editar" class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-zinc-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>

                            <form action="${duplicateUrl}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${csrf}">
                                <button type="submit" title="Duplicar" class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-zinc-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                </button>
                            </form>

                            <form action="${destroyUrl}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${csrf}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" title="Eliminar" class="p-1 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" onclick="confirmDelete(this.closest('form'))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
        }, {
            once: true
        });
    </script>
</x-layouts::app>