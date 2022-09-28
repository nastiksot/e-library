<?php

declare(strict_types=1);

namespace App\DTO;

use OpenApi\Annotations as OA;

class ErrorDTO
{
    private int $code;

    private string $message;

    /**
     * @OA\Property(
     *     property="errors",
     *     type="object",
     *     @OA\Property(
     *         property="global",
     *         type="array",
     *         @OA\Items(type="string")
     *     ),
     *     @OA\Property(
     *         property="fields",
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="field_name",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             ),
     *         )
     *     ),
     * )
     */
    private ?array $errors;

    public function __construct(int $code, string $message, ?array $errors = null)
    {
        $this->code    = $code;
        $this->message = $message;
        $this->errors  = $errors;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
