<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CarController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cars = Cache::remember('cars:all', 60, function () {
            return Car::all();
        });

        return CarResource::collection($cars);
    }

    public function store(Request $request): CarResource
    {
        $maxYear = (int) date('Y');

        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => "required|integer|min:1900|max:$maxYear",
            'price' => 'required|numeric|min:0',
        ]);

        $car = Car::create($validated);
        Cache::forget('cars:all');

        return new CarResource($car);
    }

    public function show(Car $car): CarResource
    {
        return new CarResource($car);
    }

    public function update(Request $request, Car $car): CarResource
    {
        $maxYear = (int) date('Y') + 1;

        $validated = $request->validate([
            'make' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'year' => "sometimes|required|integer|min:1900|max:$maxYear",
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $car->update($validated);
        Cache::forget('cars:all');

        return new CarResource($car);
    }

    public function destroy(Car $car): Response
    {
        $car->delete();
        Cache::forget('cars:all');

        return response()->noContent();
    }
}
