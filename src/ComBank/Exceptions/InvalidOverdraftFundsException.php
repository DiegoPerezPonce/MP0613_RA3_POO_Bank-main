<?php namespace ComBank\Exceptions;


class InvalidOverdraftFundsException extends BaseExceptions
{
    protected $errorCode = 200;
    protected $errorLabel = 'InvalidOverdraftFundsException';
}
