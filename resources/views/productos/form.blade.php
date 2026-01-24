<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:field>
            <flux:label>{{ __('Restaurante') }}</flux:label>
            <flux:select name="restaurante_id">
                @foreach($restaurantes as $restaurante)
                <option value="{{ $restaurante->id }}" {{ old('restaurante_id', $producto->restaurante_id ?? '') == $restaurante->id ? 'selected' : '' }}>
                    {{ $restaurante->nombre }}
                </option>
                @endforeach
            </flux:select>
            <flux:error name="restaurante_id" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Categoría') }}</flux:label>
            <flux:select name="categoria_id">
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }} ({{ $categoria->restaurante->nombre }})
                </option>
                @endforeach
            </flux:select>
            <flux:error name="categoria_id" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>{{ __('Nombre del Producto') }}</flux:label>
        <flux:input name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" required />
        <flux:error name="nombre" />
    </flux:field>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:field>
            <flux:label>{{ __('Precio') }}</flux:label>
            <flux:input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio ?? '') }}" required />
            <flux:error name="precio" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Estado') }}</flux:label>
            <flux:select name="activo">
                <option value="1" {{ old('activo', $producto->activo ?? 1) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                <option value="0" {{ old('activo', $producto->activo ?? 1) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
            </flux:select>
        </flux:field>
    </div>

    <flux:field>
        <flux:label>{{ __('Notas / Descripción') }}</flux:label>
        <flux:textarea name="notas">{{ old('notas', $producto->notas ?? '') }}</flux:textarea>
        <flux:error name="notas" />
    </flux:field>
</div>