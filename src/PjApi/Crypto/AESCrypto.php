<?php

namespace Neon\PjApi\Crypto;

use phpseclib\Crypt\AES;

class AESCrypto implements Crypto
{

    private $key;
    private $iv;

    public function __construct($key, $iv)
    {
        $this->key = implode(array_map("chr", $key));
        $this->iv = implode(array_map("chr", $iv));
    }

    public function encrypt($dataToEncrypt)
    {
        $aes = new AES();
        $aes->setKey($this->key);
        $aes->setIV($this->iv);

        return base64_encode($aes->encrypt($dataToEncrypt));
    }

    public function decrypt($dataToDecrypt)
    {
        $aes = new AES();
        $aes->setKey($this->key);
        $aes->setIV($this->iv);

        return $aes->decrypt(base64_decode($dataToDecrypt));
    }

}