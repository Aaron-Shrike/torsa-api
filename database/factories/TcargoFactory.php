<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tcargo;

class TcargoFactory extends Factory
{
    protected $model = Tcargo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'descripcion' => $this->faker->randomElement(['Recepcionista','Cajero','Cobrador','Promotor'])
        ];
    }
}
