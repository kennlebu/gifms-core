<?php

namespace App\Exceptions;


class NotFullyAllocatedException extends GIFMSException
{
    
    /**
     * @var int
     */
    protected $statusCode = 403;
}
