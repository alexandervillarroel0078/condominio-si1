<?php
// database/factories/EmpresaExternaFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaExternaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company,
            'servicio' => $this->faker->word,
            'telefono' => $this->faker->phoneNumber,
            'correo' => $this->faker->safeEmail,
            'direccion' => $this->faker->address,
            'observacion' => $this->faker->sentence,
        ];
    }
}
