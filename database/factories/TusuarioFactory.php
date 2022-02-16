<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tusuario;

class TusuarioFactory extends Factory
{
    protected $model = Tusuario::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'descripcion' => $this->faker
            ->randomElement(['Promotor','Recepcionista','Cajera'])
        ];
    }
}
