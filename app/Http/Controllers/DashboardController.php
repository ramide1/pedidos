<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $restaurantes = $user->restaurantes()->latest()->get();
        $restaurante_id = $restaurantes->pluck('id');
        if ($restaurante_id->isEmpty()) {
            $totalPedidos = $totalVentas = $totalProductos = $userRestaurantesCount = 0;
            $pedidosPorMes = collect();
        } else {
            $totalPedidos = Pedido::whereIn('restaurante_id', $restaurante_id)->count();
            $totalVentas = Pedido::whereIn('restaurante_id', $restaurante_id)->sum('total');
            $totalProductos = Producto::whereIn('restaurante_id', $restaurante_id)->count();
            $tipo_conexion = env('DB_CONNECTION', 'sqlite');
            $formato_fecha = match ($tipo_conexion) {
                'mysql' => "DATE_FORMAT(created_at, '%Y-%m')",
                'pgsql' => "TO_CHAR(created_at, 'YYYY-MM')",
                'sqlite' => "strftime('%Y-%m', created_at)",
                'sqlsrv' => "FORMAT(created_at, 'yyyy-MM')",
                default => "CONCAT(YEAR(created_at), '-', LPAD(MONTH(created_at), 2, '0'))"
            };
            $pedidosPorMes = Pedido::whereIn('restaurante_id', $restaurante_id)
                ->selectRaw("{$formato_fecha} as month, COUNT(*) as count")
                ->groupBy(DB::raw($formato_fecha))
                ->orderByDesc('month')
                ->limit(6)
                ->get()
                ->reverse();
            $userRestaurantesCount = $restaurantes->count();
        }
        return view('dashboard', compact('totalPedidos', 'totalVentas', 'totalProductos', 'pedidosPorMes', 'userRestaurantesCount'));
    }
}
