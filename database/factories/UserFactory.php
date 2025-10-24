<?php

// Declaración del namespace - indica que esta clase pertenece al directorio Database\Factories
namespace Database\Factories;

// Importaciones necesarias para el funcionamiento de la Factory
use Illuminate\Database\Eloquent\Factories\Factory;  // Clase base para crear factories
use Illuminate\Support\Facades\Hash;                 // Para hashear contraseñas
use Illuminate\Support\Str;                          // Para generar strings aleatorios

/**
 * UserFactory - Factory para generar datos de prueba de usuarios
 * 
 * ¿Qué es una Factory?
 * Una Factory es una clase que nos permite generar datos de prueba (fake data) 
 * de manera automática y consistente. Es especialmente útil para:
 * - Testing (pruebas unitarias e integración)
 * - Seeding (llenar la base de datos con datos iniciales)
 * - Desarrollo (crear datos de ejemplo para probar la aplicación)
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Propiedad estática para almacenar la contraseña hasheada
     * 
     * ¿Por qué es estática?
     * - Se comparte entre todas las instancias de la factory
     * - Evita hashear la misma contraseña múltiples veces (optimización)
     * - El operador ??= asigna el valor solo si la variable es null
     */
    protected static ?string $password;

    /**
     * Método principal que define el estado por defecto del modelo
     * 
     * Este método se ejecuta cada vez que creamos un nuevo usuario con la factory
     * y define qué datos se generarán automáticamente.
     * 
     * @return array<string, mixed> Array asociativo con los campos del usuario
     */
    public function definition(): array
    {
        return [
            // Genera un nombre aleatorio usando la librería Faker
            'name' => fake()->name(),
            
            // Genera un email único y seguro para pruebas
            // unique() garantiza que no se repita el email
            // safeEmail() genera emails válidos pero ficticios
            'email' => fake()->unique()->safeEmail(),
            
            // Marca el email como verificado con la fecha/hora actual
            // Esto simula un usuario que ya completó la verificación
            'email_verified_at' => now(),
            
            // Genera una contraseña hasheada
            // static::$password ??= Hash::make('password') significa:
            // - Si $password es null, hashea 'password' y lo asigna
            // - Si ya tiene valor, usa el valor existente (optimización)
            'password' => static::$password ??= Hash::make('password'),
            
            // Genera un token aleatorio de 10 caracteres para "recordar usuario"
            // Este token se usa para mantener la sesión del usuario
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Método para crear usuarios con email NO verificado
     * 
     * Este es un ejemplo de "state" en las factories de Laravel.
     * Los states nos permiten modificar los datos por defecto para casos específicos.
     * 
     * ¿Cuándo usar este método?
     * - Para probar el flujo de verificación de email
     * - Para simular usuarios que no han completado el registro
     * - Para testing de funcionalidades que requieren email verificado
     * 
     * @return static Retorna la misma instancia para permitir method chaining
     */
    public function unverified(): static
    {
        // Usa el método state() para modificar los atributos
        // fn() es una arrow function (función flecha) de PHP 7.4+
        // Recibe los atributos actuales y retorna los modificados
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,  // Establece email_verified_at como null
        ]);
    }
}
