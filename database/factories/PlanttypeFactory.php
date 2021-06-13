<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Planttype;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanttypeFactory extends Factory {
    /**
    * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Planttype::class;

    /**
     * Define the model's default state.
    *
    * @return array
    */

    public function definition() {
        return [
            'type' => 'outdoor',
            'details' => 'Outdoor plants',
        ];
    }
}
