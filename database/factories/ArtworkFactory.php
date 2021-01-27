<?php

namespace Database\Factories;

use App\Models\Artwork;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Artwork::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->word,
            'primary_art' => $this->faker->imageUrl($width = 640, $height = 480),
            'height' => rand(100,2000),
            'width' => rand(100,2000),
            'cost' => rand(100,2000),
            'live' => rand(0,1),
            'created_by' => rand(1,10),
        ];
    }
}
