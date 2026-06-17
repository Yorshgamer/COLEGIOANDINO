<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $first_name
 * @property string $paternal_surname
 * @property string $maternal_surname
 * @property string $dni
 * @property string|null $phone
 * @property string $email
 * @property string $status
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $last_login_at  <-- 1. AÑADIDO PARA AUTOCUBRIR EL TIPADO INTERNO
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $name
 */
#[Fillable([
    'first_name', 
    'paternal_surname', 
    'maternal_surname', 
    'dni', 
    'phone', 
    'email', 
    'password', 
    'status',
    'signature_path',
    'last_login_at' // <-- 2. PERMITIMOS LA ESCRITURA MASIVA DESDE EL EVENTO DE LOGIN
])]

#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes;

    protected $appends = ['name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime', // <-- 3. CASTEO AUTOMÁTICO A CARBON PARA EVITAR ERRORES DE STRING
        ];
    }

    /**
     * 3. ACCESSOR: Concatena los nombres y apellidos de forma transparente.
     * Al consultar $user->name en Blade o user.name en Vue 3, devolverá la cadena armada.
     */
    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->paternal_surname} {$this->maternal_surname}";
    }
}