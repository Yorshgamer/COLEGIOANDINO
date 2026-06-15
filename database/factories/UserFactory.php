<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            // Reemplazamos 'name' por los campos atómicos peruanos usando Faker
            'first_name' => fake()->firstName(),
            'paternal_surname' => fake()->lastName(),
            'maternal_surname' => fake()->lastName(),
            
            // Generamos un DNI aleatorio único de 8 dígitos para las pruebas
            'dni' => fake()->unique()->numerify('########'),
            'phone' => fake()->optional()->numerify('9########'),
            
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            
            // Contraseña por defecto para usuarios faker (password)
            'password' => static::$password ??= Hash::make('password'),
            
            'status' => 'Activo',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
