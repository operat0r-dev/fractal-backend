<?php
namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    protected int $httpCode;
    protected array $data;
    protected string $message;

    public function __construct(int $httpCode, array $data = [], string $message = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->message = $message;
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 500 => ['message' => 'Internal server error'],
            $this->httpCode >= 400 => ['message' => $this->message],
            $this->httpCode >= 200 => ['data' => $this->data],
        };

        return response()->json($payload, $this->httpCode, [], JSON_UNESCAPED_UNICODE);
    }

    public static function ok(array $data)
    {
        return new static(200, $data);
    }

    public static function created(array $data)
    {
        return new static(201, $data);
    }

    public static function notFound(string $message = "Item not found")
    {
        return new static(404, message: $message);
    }

    public static function badRequest(string $message = "Validation error")
    {
        return new static(400, message: $message);
    }

    public static function conflict(string $message)
    {
        return new static(409, message: $message);
    }
}
?>