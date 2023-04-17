<?php
namespace Support\Exceptions;

use Support\BaseClasses\BaseException;

class APIException extends BaseException
{
    protected int $statusCode = 500;

    protected string $errorCode = 'unknown_api_error';

    protected $message = 'Unknown error';

    protected array $errors = [];

    public function render($request): \Illuminate\Http\JsonResponse
    {
        $errorData = [
            'status'     => 'error',
            'error_code' => $this->errorCode,
            'message'    => $this->message,
            'errors'     => method_exists($this, 'errors') ? $this->errors() : $this->errors,

        ];

        return response()
            ->json($errorData)
            ->setStatusCode($this->statusCode);
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setErrorCode(string $code): self
    {
        $this->errorCode = $code;

        return $this;
    }

    /**
     * Sets additional error messages for better error responses.
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
