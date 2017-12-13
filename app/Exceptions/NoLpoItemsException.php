<?php

namespace App\Exceptions;


class NoLpoItemsException extends GIFMSException
{
    
    /**
     * @var int
     */
    protected $statusCode = 403;
}
