<?php

namespace Database\Factories;

use App\Models\SubPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubPage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => "test",
            "title" => "Testing sub page.",
            "description" => $this->faker->paragraph
        ];
    }
}
