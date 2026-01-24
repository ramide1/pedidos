<div class="space-y-6">
    <flux:field>
        <flux:label>{{ __('Nombre') }}</flux:label>
        <flux:input name="nombre" value="{{ old('nombre', $restaurante->nombre ?? '') }}" required />
        <flux:error name="nombre" />
    </flux:field>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:field>
            <flux:label>{{ __('Teléfono') }}</flux:label>
            <flux:input name="telefono" value="{{ old('telefono', $restaurante->telefono ?? '') }}" required />
            <flux:error name="telefono" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Email') }}</flux:label>
            <flux:input type="email" name="email" value="{{ old('email', $restaurante->email ?? '') }}" />
            <flux:error name="email" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>{{ __('Dirección') }}</flux:label>
        <flux:input name="direccion" value="{{ old('direccion', $restaurante->direccion ?? '') }}" required />
        <flux:error name="direccion" />
    </flux:field>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:field>
            <flux:label>{{ __('Tipo de Cocina') }}</flux:label>
            <flux:select name="tipo_cocina">
                @foreach(['italiana', 'mexicana', 'china', 'rapida', 'vegetariana', 'vegana', 'postres', 'otros'] as $tipo)
                <option value="{{ $tipo }}" {{ old('tipo_cocina', $restaurante->tipo_cocina ?? '') == $tipo ? 'selected' : '' }}>
                    {{ ucfirst($tipo) }}
                </option>
                @endforeach
            </flux:select>
            <flux:error name="tipo_cocina" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Estado') }}</flux:label>
            <flux:select name="activo">
                <option value="1" {{ old('activo', $restaurante->activo ?? 1) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                <option value="0" {{ old('activo', $restaurante->activo ?? 1) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
            </flux:select>
        </flux:field>
    </div>

    @if(auth()->user()->admin)
    <flux:field>
        <flux:label>{{ __('Administradores del Restaurante') }}</flux:label>
        <flux:select name="user_ids[]" multiple>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', $selectedUserIds ?? [])) ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
            @endforeach
        </flux:select>
        <flux:error name="user_ids" />
    </flux:field>
    @endif

    <flux:field>
        <flux:label>{{ __('Notas') }}</flux:label>
        <flux:textarea name="notas">{{ old('notas', $restaurante->notas ?? '') }}</flux:textarea>
        <flux:error name="notas" />
    </flux:field>
</div>