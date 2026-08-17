<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Boleta extends Model
{
    /**
     * Campos permitidos para asignación masiva de datos.
     */
    protected $fillable = [
        'user_id',
        'type',
        'mes',
        'anio',
        'pdf_path',
        'status',
        'signed_at',
        'read_at',
        'observation_message',
        'notified_at',
    ];

    /**
     * Conversión automática de tipos de datos a objetos Carbon / Datetime.
     */
    protected $casts = [
        'notified_at' => 'datetime',
        'signed_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Relación: Una boleta pertenece de forma obligatoria a un Trabajador (User)
     */
    /**
     * Relación: Una boleta pertenece de forma obligatoria a un Trabajador (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
