<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CarController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CarResource::collection(Car::all());
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

        return new CarResource($car);
    }

    public function destroy(Car $car): Response
    {
        $car->delete();

        return response()->noContent();
    }
}
