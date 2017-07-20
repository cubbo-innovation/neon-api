<?php

namespace Neon\PjApi\Crypto;

use phpseclib\Crypt\RSA;

class RSACrypto implements Crypto
{

    private $publicKey;
    private $privateKey;

    public function __construct($privateKey, $publicKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function encrypt($dataToEncrypt)
    {
        $rsa = new RSA();
        $rsa->loadKey($this->publicKey);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);

        return base64_encode($rsa->encrypt($dataToEncrypt));
    }

    public function decrypt($dataToDecrypt)
    {
        $rsa = new RSA();
        $rsa->loadKey($this->privateKey);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);

        $dataToDecrypt = str_replace(' ','+', $dataToDecrypt);

        return $rsa->decrypt(base64_decode($dataToDecrypt));
    }

}