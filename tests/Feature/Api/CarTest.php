<?php

namespace Tests\Feature\Api;

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_cars_list(): void
    {
        $car = Car::factory()->create();

        $this->getJson(route('cars.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$car->toArray()],
            ]);
    }

    public function test_api_car_store_successful()
    {
        $carData = [
            'make' => 'Opel',
            'model' => 'Corsa',
            'year' => 2007,
            'price' => 45000,
        ];

        $this->postJson(route('cars.store'), $carData)
            ->assertStatus(201)
            ->assertJsonFragment($carData);

        $this->assertDatabaseHas('cars', $carData);
    }

    public function test_api_car_update_successful()
    {
        $car = Car::factory()->create();

        $updateData = [
            'make' => 'Ford',
            'model' => 'Focus',
            'year' => 2010,
            'price' => 32000,
        ];

        $this->putJson(route('cars.update', $car), $updateData)
            ->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('cars', $updateData);
    }
}
