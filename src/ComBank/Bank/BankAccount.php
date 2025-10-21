<?php namespace ComBank\Bank;

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
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
    // Cambiamos 'bool' a 'string' para que pueda almacenar BankAccountInterface::STATUS_OPEN o STATUS_CLOSED
    private string $status; 

    private OverdraftInterface $overdraft;

    /**
     *  @param float $newBalance;
     *  @throws InvalidArgsException
     *  @throws ZeroAmountException
     * 
     */
   
    public function __construct(float $newBalance = 0.0)
    {
        $this->validateAmount(amount:$newBalance);
        $this->balance = $newBalance;
        $this->status = BankAccountInterface::STATUS_OPEN; // Establecemos el estado inicial como OPEN
        $this->overdraft = new NoOverdraft(); // Inicializamos con una estrategia de no sobregiro por defecto
    }

    public function reopenAccount(): void
    {
        if ($this->isOpen()) {
            throw new BankAccountException(message:'Bank account already opened');
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount(): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException(message:'Bank account already closed.');
        }
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function transaction(BankTransactionInterface $bankTransaction) : void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException("account is closed");
        }
        $bankTransaction->applyTransaction($this);
    }

    public function isOpen() : bool
    {
        // Más conciso y preciso al comparar con la constante de string
        return $this->status === BankAccountInterface::STATUS_OPEN; 
    }
    
    public function getOverdraft() : OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft) : void
    {
        $this->overdraft = $overdraft;
    }

    public function setBalance(float $balance) : void
    {
        // Podrías añadir validación aquí si el balance no puede ser negativo, por ejemplo.
        // O si quieres validar el monto antes de establecerlo.
        try {
            $this->validateAmount(amount: $balance);
            $this->balance = $balance;
        } catch (InvalidArgsException $e) {
            // Manejar la excepción, por ejemplo, relanzándola o registrándola
            throw new BankAccountException("No se pudo establecer el saldo: " . $e->getMessage());
        } catch (ZeroAmountException $e) {
            // Si quieres permitir 0 como balance, podrías omitir esta validación o ajustarla
            // Para este caso, asumimos que 0 es un balance válido.
            $this->balance = $balance;
        }
    }
}