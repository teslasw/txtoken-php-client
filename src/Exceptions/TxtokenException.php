<?php

namespace Txtoken\Exceptions;

use Exception;

/**
 * Generic API exception
 */
class TxtokenException extends Exception
{
    /**
     * Last response from API that triggered this exception
     *
     * @var string
     */
    public $rawResponse;
}