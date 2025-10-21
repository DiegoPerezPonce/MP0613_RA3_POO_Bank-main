<?php namespace ComBank\Exceptions;

class InvalidArgsException extends BaseExceptions
{
    protected $errorCode = 100;
    protected $errorLabel = 'InvalidArgsException';
}
