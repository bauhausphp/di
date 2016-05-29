<?php

namespace Bauhaus\DependencyInjection\Exception;

class DependencyInjectionServiceNotFoundException extends \Exception
{
    public function __construct($serviceName)
    {
        parent::__construct("No service found with the name '$serviceName'");
    }
}
