<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Insufficient stock available', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
