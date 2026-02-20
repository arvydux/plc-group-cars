<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makes = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Yaris', 'Land Cruiser', 'Highlander'],
            'Honda' => ['Civic', 'Accord', 'CR-V'],
            'Ford' => ['Mustang', 'F-150', 'Focus', 'Explorer'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan'],
            'Mazda' => ['3', '6', 'CX-5', 'MX-5'],
            'Subaru' => ['Impreza', 'Outback', 'Forester'],
        ];

        $make = fake()->randomElement(array_keys($makes));

        return [
            'make' => $make,
            'model' => fake()->randomElement($makes[$make]),
            'year' => fake()->numberBetween(2000, 2026),
            'price' => fake()->randomFloat(0, 2000, 100000),
        ];
    }
}
