<?php

namespace Database\Factories;

use App\Models\ContactoEmergencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactoEmergenciaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactoEmergencia::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=>$this->faker->name(),
            'numero'=>$this->faker->numberBetween($min = 111111111, $max = 999999999),
            'parentesco'=>$this->faker->randomElement(['Padre','Hermano','Hijo','Tio'])
        ];
    }
}
