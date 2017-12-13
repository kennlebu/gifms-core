<?php

namespace App\Exceptions;


class LpoQuotationAmountMismatchException extends GIFMSException
{
    
    /**
     * @var int
     */
    protected $statusCode = 403;
}
