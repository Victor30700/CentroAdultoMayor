<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdultoMayor extends Model
{
    use HasFactory;

    protected $table = 'adulto_mayor'; // Nombre de la tabla
    protected $primaryKey = 'id_adulto'; // Clave primaria

    protected $fillable = [
        'ci',
        'discapacidad',
        'vive_con',
        'migrante',
        'nro_caso',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'migrante' => 'boolean',
    ];

    /**
     * Obtiene la información de la persona para este adulto mayor.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'ci', 'ci');
    }
    // RELACIONES CON ACTIVIDAD LABORAL, ENCARGADO
    public function actividadLaboral()
    {
        return $this->hasOne(ActividadLaboral::class, 'id_adulto');
    }

    public function encargados()
    {
        return $this->hasMany(Encargado::class, 'id_adulto');
    }

}