<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaNatural extends Model
{
    use HasFactory;

    protected $table = 'persona_natural';
    protected $primaryKey = 'id_natural';
    public $timestamps = false;

    protected $fillable = [
        'id_encargado',
        'primer_apellido',
        'segundo_apellido',
        'nombres',
        'edad',
        'ci',
        'telefono',
        'direccion_domicilio',
        'relacion_parentesco',
        'direccion_de_trabajo',
        'ocupacion',
    ];

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'id_encargado');
    }
}
