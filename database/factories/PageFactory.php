<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug(2),
            'title' => $this->faker->sentence(),
            'excerpt' => $this->faker->paragraphs(1, true),
            'content' => $this->faker->paragraphs(4, true),
        ];
    }
}
