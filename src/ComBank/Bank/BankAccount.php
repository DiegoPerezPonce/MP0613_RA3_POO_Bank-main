<?php
namespace ComBank\Bank;

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    use AmountValidationTrait;
    
    private float $balance;
    private string $status;
    private ?OverdraftInterface $overdraft; // ⚡ Puede ser null

    /**
     * @param float $newBalance
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function __construct(float $newBalance = 0.0)
    {
        $this->validateAmount(amount: $newBalance);
        $this->balance = $newBalance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        $this->overdraft = null; // ⚡ Sin sobregiro por defecto
    }

    public function reopenAccount(): void
    {
        if ($this->isOpen()) {
            throw new BankAccountException(message: 'Bank account already opened');
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount(): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException(message: 'Bank account already closed.');
        }
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function transaction(BankTransactionInterface $bankTransaction): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException("account is closed");
        }

        try {
            $bankTransaction->applyTransaction($this);
        } catch (FailedTransactionException $e) {
            throw $e;
        } catch (InvalidOverdraftFundsException $e) {
            throw $e;
        }
    }

    public function isOpen(): bool
    {
        return $this->status === BankAccountInterface::STATUS_OPEN;
    }

    public function getOverdraft(): ?OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }

    public function setBalance(float $balance): void
    {
        try {
            $this->validateAmount(amount: $balance);
            $this->balance = $balance;
        } catch (InvalidArgsException $e) {
            throw new BankAccountException("No se pudo establecer el saldo: " . $e->getMessage());
        } catch (ZeroAmountException $e) {
            $this->balance = $balance;
        }
    }
}
