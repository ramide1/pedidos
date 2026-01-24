<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestauranteUser extends Model
{
    protected $table = 'restaurantes_users';

    protected $fillable = [
        'restaurante_id',
        'user_id',
        'activo'
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
