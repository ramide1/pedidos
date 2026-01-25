<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $pedidos = Pedido::with('restaurante:id,nombre')->whereIn('restaurante_id', $user->restaurantes()->pluck('restaurantes.id'))->latest()->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $pedido->load(['restaurante', 'items.producto']);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$pedido->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para editar este pedido'));
        return view('pedidos.edit', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$pedido->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para editar este pedido'));
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,en_camino,entregado,cancelado',
            'pago_confirmado' => 'boolean',
            'notas' => 'nullable|string',
        ]);
        $pedido->update($validated);
        return redirect()->route('pedidos.index')->with('success', __('Pedido actualizado con éxito.'));
    }

    public function destroy(Pedido $pedido)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        if (!$pedido->restaurante->users()->where('users.id', $user_id)->exists()) abort(403, __('No tienes permiso para eliminar este pedido'));
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', __('Pedido eliminado con éxito.'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'nombre' => 'required|string',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'restaurante_id' => 'required|exists:restaurantes,id',
            'items' => 'required|array',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'notas' => 'nullable|string',
        ]);
        $subtotal = 0;
        $itemsToCreate = [];
        foreach ($validated['items'] as $itemData) {
            $producto = Producto::where('id', $itemData['producto_id'])->first();
            if (!$producto) abort(404, __('Producto no encontrado'));
            $subtotal += $producto->precio * $itemData['cantidad'];
            $itemsToCreate[] = [
                'producto_id' => $producto->id,
                'cantidad' => $itemData['cantidad'],
                'precio' => $producto->precio
            ];
        }
        // cambiarlo despues
        $costoEnvio = 0;
        $total = $subtotal + $costoEnvio;
        $pedido = Pedido::create([
            'email' => $validated['email'],
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
            'notas' => $validated['notas'],
            'restaurante_id' => $validated['restaurante_id'],
            'subtotal' => $subtotal,
            'costo_envio' => $costoEnvio,
            'total' => $total,
            'codigo' => strtoupper(uniqid('ORD-')),
            'user_id' => Auth::id(),
            'estado' => 'pendiente'
        ]);
        foreach ($itemsToCreate as $item) {
            $pedido->items()->create($item);
        }
        return redirect()->route('home')->with('success', __('¡Pedido #' . $pedido->codigo . ' realizado con éxito! El restaurante lo gestionará pronto.'));
    }
}
