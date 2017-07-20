<?php

namespace Neon\PjApi\Operations;

use Neon\PjApi\Client;

abstract class AbstractOperation
{

    protected $request;

    abstract public function execute(Client $client);

    public function getRequest()
    {
        return $this->request;
    }

}