<?php

namespace Database\Factories;

use App\Models\CargoEmpleado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'ci' => $this->faker->unique()->numerify('########'),
            'estado' => 1,
            'fecha_ingreso' => $this->faker->date(),
            'fecha_salida' => null,
            'cargo_empleado_id' => CargoEmpleado::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
