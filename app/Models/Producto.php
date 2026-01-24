<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'restaurante_id',
        'nombre',
        'notas',
        'precio',
        'categoria_id',
        'activo',
        'imagen'
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }
}
