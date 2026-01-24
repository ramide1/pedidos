<div class="space-y-6">
    <flux:field>
        <flux:label>{{ __('Restaurante') }}</flux:label>
        <flux:select name="restaurante_id">
            @foreach($restaurantes as $restaurante)
            <option value="{{ $restaurante->id }}" {{ old('restaurante_id', $categoria->restaurante_id ?? '') == $restaurante->id ? 'selected' : '' }}>
                {{ $restaurante->nombre }}
            </option>
            @endforeach
        </flux:select>
        <flux:error name="restaurante_id" />
    </flux:field>

    <flux:field>
        <flux:label>{{ __('Nombre') }}</flux:label>
        <flux:input name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}" required />
        <flux:error name="nombre" />
    </flux:field>

    <flux:field>
        <flux:label>{{ __('Estado') }}</flux:label>
        <flux:select name="activo">
            <option value="1" {{ old('activo', $categoria->activo ?? 1) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
            <option value="0" {{ old('activo', $categoria->activo ?? 1) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
        </flux:select>
    </flux:field>
</div>