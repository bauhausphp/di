<?php

namespace Bauhaus\DI\Exception;

class DIServiceNotFound extends \Exception
{
    public function __construct($label)
    {
        parent::__construct("No service with label '$label' was found in this dependency injection container");
    }
}
