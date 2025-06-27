<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bill;

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'currency' => 'CAD',
            'status' => fake()->randomElement(["draft","sent","paid"]),
            'issued_at' => fake()->dateTime(),
            'due_at' => fake()->dateTime(),
            'discount' => fake()->randomFloat(0, 0, 100),
        ];
    }
}
