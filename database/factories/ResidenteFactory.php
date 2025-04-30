<?php

namespace Database\Factories;

use App\Models\Residente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidenteFactory extends Factory
{
    protected $model = Residente::class;

    public function definition(): array
    {
        $tipos = ['Propietario', 'Inquilino', 'Otro'];

        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'ci' => $this->faker->unique()->numerify('########'),
            'email' => $this->faker->unique()->safeEmail(),
            'tipo_residente' => $this->faker->randomElement($tipos),
        ];
    }
}
