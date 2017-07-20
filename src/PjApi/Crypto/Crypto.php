<?php

namespace Neon\PjApi\Crypto;

interface Crypto
{

    public function encrypt($dataToEncrypt);

    public function decrypt($dataToDecrypt);

}