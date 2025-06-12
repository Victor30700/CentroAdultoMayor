<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denunciado extends Model
{
    protected $table = 'denunciado';
    protected $primaryKey = 'id_denunciado';
    public $timestamps = false;

    protected $fillable = [
        'id_natural',
        'sexo',
        'descripcion_hechos',
    ];

    public function personaNatural()
    {
        return $this->belongsTo(PersonaNatural::class, 'id_natural');
    }
}
