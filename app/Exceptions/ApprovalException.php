<?php

namespace App\Exceptions;


class ApprovalException extends GIFMSException
{
    
    /**
     * @var int
     */
    protected $statusCode = 401;
}
