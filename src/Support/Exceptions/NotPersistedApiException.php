<?php
namespace Support\Exceptions;


class NotPersistedApiException extends APIException
{
    protected int $statusCode = 500;

    protected string $errorCode = 'not_persisted';

    protected $message = 'Resource could not be persisted. Try again later.';
}
