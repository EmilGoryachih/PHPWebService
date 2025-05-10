<?php
namespace CarCatalog\App\Controllers\Api;

use CarCatalog\App\Models\Car;

class CarApiController
{
    // GET /api/cars
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $cars = Car::getAll();
        echo json_encode($cars, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    // GET /api/cars/{id}
    public function show(int $id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $car = Car::getById($id);

        if (!$car) {
            http_response_code(404);
            echo json_encode(['error' => 'Автомобиль не найден'], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode($car, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}
