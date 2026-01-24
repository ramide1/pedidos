<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'email',
        'nombre',
        'telefono',
        'direccion',
        'notas',
        'entrega',
        'estado',
        'subtotal',
        'costo_envio',
        'total',
        'metodo_pago',
        'pago_confirmado',
        'restaurante_id',
        'codigo',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
