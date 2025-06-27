<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Bill;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => fake()->text(50),
            'quantity' => fake()->numberBetween(0, 200),
            'unit_price' => fake()->randomFloat(2, 0, 999.99),
            'bill_id' => Bill::factory(),
        ];
    }
}
