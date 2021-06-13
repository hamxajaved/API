<?php

namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlantFactory extends Factory {
    /**
    * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plant::class;

    /**
     * Define the model's default state.
    *
    * @return array
    */

    public function definition() {
        return [
            'name'=> 'safeda',
            'price'=> rand( 50, 200 ),
            'stock' => rand( 10, 100 ),
            'plant_type_id'=> rand( 1, 10 ),
            'description' => 'Eucalyptus globulus, commonly known as southern blue gum or blue gum or Safeda, is a species of tall, evergreen tree endemic to southeastern Australia',
        ];
    }
}
