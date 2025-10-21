<?php namespace ComBank\Exceptions;


class BankAccountException extends BaseExceptions
{
    protected $errorCode = 500;
    protected $errorLabel = 'BankAccountException';
}
