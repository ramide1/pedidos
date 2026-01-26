<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $restaurantes = $user->restaurantes()->latest()->paginate(20);
        return view('restaurantes.index', compact('restaurantes'));
    }

    public function show()
    {
        return redirect()->route('home');
    }

    public function create()
    {
        $users = User::all();
        $selectedUserIds = [Auth::id()];
        return view('restaurantes.create', compact('users', 'selectedUserIds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tipo_cocina' => 'required|string',
            'notas' => 'nullable|string',
            'imagen' => 'nullable|string',
            'activo' => 'boolean',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        $restaurante = Restaurante::create($validated);
        $restaurante->users()->sync($validated['user_ids']);
        return redirect()->route('restaurantes.index')->with('success', __('Restaurante creado con éxito.'));
    }

    public function edit(Restaurante $restaurante)
    {
        if (!$restaurante->users()->where('user_id', Auth::id())->exists()) abort(403, __('No tienes permiso para editar este restaurante.'));
        $users = User::all();
        $selectedUserIds = $restaurante->users()->pluck('users.id')->toArray();
        return view('restaurantes.edit', compact('restaurante', 'users', 'selectedUserIds'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        if (!$restaurante->users()->where('user_id', Auth::id())->exists()) abort(403, __('No tienes permiso para editar este restaurante.'));
        $rules = [
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tipo_cocina' => 'required|string',
            'notas' => 'nullable|string',
            'imagen' => 'nullable|string',
            'activo' => 'boolean',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ];
        $validated = $request->validate($rules);
        $restaurante->update($validated);
        $restaurante->users()->sync($validated['user_ids']);
        return redirect()->route('restaurantes.index')->with('success', __('Restaurante actualizado con éxito.'));
    }

    public function destroy(Restaurante $restaurante)
    {
        if (!$restaurante->users()->where('user_id', Auth::id())->exists()) abort(403, __('No tienes permiso para eliminar este restaurante.'));
        $restaurante->delete();
        return redirect()->route('restaurantes.index')->with('success', __('Restaurante eliminado con éxito.'));
    }

    public function duplicate(Restaurante $restaurante)
    {
        if (!$restaurante->users()->where('user_id', Auth::id())->exists()) abort(403, __('No tienes permiso para duplicar este restaurante.'));
        $new_restaurante = $restaurante->replicate();
        $new_restaurante->nombre = $new_restaurante->nombre . __(' (Copia)');
        $new_restaurante->save();
        $new_restaurante->users()->sync($restaurante->users->pluck('id'));
        return redirect()->route('restaurantes.index')->with('success', __('Restaurante duplicado con éxito.'));
    }

    public function menu(Restaurante $restaurante)
    {
        $restaurante->categorias()->where('activo', true)->with(['productos' => function ($q) {
            $q->where('activo', true);
        }])->latest()->get();
        return view('restaurantes.menu', compact('restaurante'));
    }
}
