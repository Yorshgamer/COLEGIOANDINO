<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'type',
    'mes',
    'anio',
    'pdf_path',
    'status',           // <-- Cambiado por is_signed
    'signed_at',
    'observation_message' // <-- Añadido para soporte de reclamos
])]

class Boleta extends Model
{
    /**
     * Relación: Una boleta pertenece de forma obligatoria a un Trabajador (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
