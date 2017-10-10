<?php

namespace App\Exceptions;


class PermissionException extends GIFMSException
{
    
    /**
     * @var int
     */
    protected $statusCode = 401;
}
