<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function __invoke(Request $request)
    {
        $restaurantes = Restaurante::where('activo', true)->whereHas('productos')->withCount('pedidos');
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $restaurantes->where('nombre', 'like', "%{$search}%")->orWhere('tipo_cocina', 'like', "%{$search}%")->orWhere('direccion', 'like', "%{$search}%");
        }
        $restaurantes = $restaurantes->orderBy('pedidos_count', 'desc')->get();
        return view('welcome', compact('restaurantes'));
    }
}
