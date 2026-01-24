<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    protected $table = 'restaurantes';

    protected $fillable = [
        'nombre',
        'notas',
        'direccion',
        'telefono',
        'email',
        'imagen',
        'activo',
        'tipo_cocina'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'restaurantes_users');
    }

    public function categorias()
    {
        return $this->hasMany(CategoriaProducto::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
