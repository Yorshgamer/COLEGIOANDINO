<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroReclamacion extends Model
{
    use HasFactory;

    protected $table = 'libro_reclamaciones';

    protected $fillable = [
        'correlativo',
        'nombres',
        'apellidos',
        'correo',
        'domicilio',
        'dni',
        'telefono',
        'padre_madre',
        'bien_contratado',
        'descripcion_bien',
        'tipo_reclamo',
        'detalle_reclamo',
        'estado',
    ];
}