<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaProductoController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $categorias = CategoriaProducto::with('restaurante:id,nombre')->whereIn('restaurante_id', $user->restaurantes()->pluck('restaurantes.id'))->latest()->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurantes = $user->restaurantes()->latest()->get();
        return view('categorias.create', compact('restaurantes'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $validated = $request->validate([
            'restaurante_id' => 'required|exists:restaurantes,id',
            'nombre' => 'required|string|max:255',
            'activo' => 'boolean'
        ]);
        $restaurante = Restaurante::where('id', $validated['restaurante_id'])->first();
        if (!$restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para crear categorías en este restaurante'));
        CategoriaProducto::create($validated);
        return redirect()->route('categorias.index')->with('success', __('Categoría creada con éxito.'));
    }

    public function edit(CategoriaProducto $categoria)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurantes = $user->restaurantes()->latest()->get();
        return view('categorias.edit', compact('categoria', 'restaurantes'));
    }

    public function update(Request $request, CategoriaProducto $categoria)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $validated = $request->validate([
            'restaurante_id' => 'required|exists:restaurantes,id',
            'nombre' => 'required|string|max:255',
            'activo' => 'boolean',
        ]);
        if (!$categoria->restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$categoria->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para crear categorías en este restaurante'));
        $new_restaurante = Restaurante::where('id', $validated['restaurante_id'])->first();
        if (!$new_restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$new_restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para crear categorías en este restaurante'));
        $categoria->update($validated);
        return redirect()->route('categorias.index')->with('success', __('Categoría actualizada con éxito.'));
    }

    public function destroy(CategoriaProducto $categoria)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurante = Restaurante::where('id', $categoria->restaurante_id)->first();
        if (!$restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para eliminar categorías en este restaurante'));
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', __('Categoría eliminada con éxito.'));
    }

    public function duplicate(CategoriaProducto $categoria)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurante = Restaurante::where('id', $categoria->restaurante_id)->first();
        if (!$restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para duplicar categorías en este restaurante'));
        $new_categoria = $categoria->replicate();
        $new_categoria->nombre = $new_categoria->nombre . __(' (Copia)');
        $new_categoria->save();
        return redirect()->route('categorias.index')->with('success', __('Categoría duplicada con éxito.'));
    }
}
