# Cars API

All endpoints are under `/api`. Responses are JSON.

## Endpoints

`GET /api/cars` - get all cars

`GET /api/cars/{id}` - get car by id, 404 if not found

`POST /api/cars` - create car, body:
```json
{"make": "Opel", "model": "Corsa", "year": 2007, "price": 4500}
```
all fields required, returns 201

`PUT /api/cars/{id}` - update car, can send partial data:
```json
{"price": 3800}
```
returns 200, or 404

`DELETE /api/cars/{id}` - delete car, returns 204, or 404

## Validation

- `make`, `model` - string, max 255
- `year` - integer, 1900 to current year
- `price` - numeric, min 0

Returns 422 with errors if validation fails.
