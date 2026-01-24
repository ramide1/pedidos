<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();
        if (!$user) abort(403, __('No autenticado'));
        if (!$user->admin) return redirect()->route('home');
        $restaurantes = $user->restaurantes()->latest()->get();
        $restaurante_id = $restaurantes->pluck('id');
        if ($restaurante_id->isEmpty()) {
            $totalPedidos = $totalVentas = $totalProductos = $userRestaurantesCount = 0;
            $pedidosPorMes = collect();
        } else {
            $totalPedidos = Pedido::whereIn('restaurante_id', $restaurante_id)->count();
            $totalVentas = Pedido::whereIn('restaurante_id', $restaurante_id)->sum('total');
            $totalProductos = Producto::whereIn('restaurante_id', $restaurante_id)->count();
            $pedidosPorMes = Pedido::whereIn('restaurante_id', $restaurante_id)
                ->select(DB::raw('COUNT(*) as count'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(6)
                ->get()
                ->reverse();
            $userRestaurantesCount = $restaurantes->count();
        }
        return view('dashboard', compact('totalPedidos', 'totalVentas', 'totalProductos', 'pedidosPorMes', 'userRestaurantesCount'));
    }
}
