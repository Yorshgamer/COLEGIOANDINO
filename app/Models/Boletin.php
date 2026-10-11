<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boletin extends Model
{
    use HasFactory;

    protected $table = 'boletines';

    protected $fillable = [
        'numero',
        'titulo',
        'descripcion',
        'fecha_publicacion',
        'archivo_pdf',
        'is_active',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];
}
