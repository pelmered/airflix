<?php
namespace Support\Exceptions;

class NotFoundApiException extends APIException
{
    protected int $statusCode = 404;

    protected string $errorCode = 'not_found';

    protected $message = 'Not found';
}
