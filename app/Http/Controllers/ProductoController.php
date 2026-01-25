<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Restaurante;
use App\Models\CategoriaProducto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $productos = Producto::with('restaurante:id,nombre')->whereIn('restaurante_id', $user->restaurantes()->pluck('restaurantes.id'))->latest()->get();
        return view('productos.index', compact('productos'));
    }

    public function show()
    {
        return redirect()->route('home');
    }

    public function create()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurantes = $user->restaurantes()->latest()->get();
        $categorias = CategoriaProducto::with('restaurante:id,nombre')->whereIn('restaurante_id', $user->restaurantes()->pluck('restaurantes.id'))->latest()->get();
        return view('productos.create', compact('restaurantes', 'categorias'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $validated = $request->validate([
            'restaurante_id' => 'required|exists:restaurantes,id',
            'categoria_id' => 'nullable|exists:categorias_productos,id',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);
        $restaurante = Restaurante::where('id', $validated['restaurante_id'])->first();
        if (!$restaurante) abort(404, __('Restaurante no encontrado'));
        if (!$restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para crear productos en este restaurante'));
        Producto::create($validated);
        return redirect()->route('productos.index')->with('success', __('Producto creado con éxito.'));
    }

    public function edit(Producto $producto)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$producto->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para editar este producto'));
        $restaurantes = $user->restaurantes()->latest()->get();
        $categorias = CategoriaProducto::with('restaurante:id,nombre')->whereIn('restaurante_id', $user->restaurantes()->pluck('restaurantes.id'))->latest()->get();
        return view('productos.edit', compact('producto', 'restaurantes', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$producto->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para editar este producto'));
        $validated = $request->validate([
            'restaurante_id' => 'required|exists:restaurantes,id',
            'categoria_id' => 'nullable|exists:categorias_productos,id',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);
        $new_restaurante = Restaurante::findOrFail($validated['restaurante_id']);
        if (!$new_restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para editar este producto'));
        $producto->update($validated);
        return redirect()->route('productos.index')->with('success', __('Producto actualizado con éxito.'));
    }

    public function destroy(Producto $producto)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$producto->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para eliminar este producto'));
        $producto->delete();
        return redirect()->route('productos.index')->with('success', __('Producto eliminado con éxito.'));
    }

    public function duplicate(Producto $producto)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$producto->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para duplicar este producto'));
        $new_producto = $producto->replicate();
        $new_producto->nombre = $new_producto->nombre . __(' (Copia)');
        $new_producto->save();
        return redirect()->route('productos.index')->with('success', __('Producto duplicado con éxito.'));
    }
}
