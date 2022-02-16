<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cemergencia;

class CemergenciaFactory extends Factory
{
    protected $model = Cemergencia::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=>$this->faker->name(),
            'numero'=>$this->faker->phoneNumber(),
            'parentesco'=>$this->faker->randomElement(['Padre','Hermano','Hijo','Tio'])
        ];
    }
}
