<?php
require 'vendor/autoload.php';

use Neon\PjApi\Client;
use Neon\PjApi\Operations\AuthenticationOperation;
use Neon\PjApi\Operations\SendTransferOperation;
use Neon\PjApi\Crypto\RSACrypto;
use Neon\PjApi\Crypto\AESCrypto;

$clientId      = 1;
$bankAccountId = 1;
$token         = 'your-token';
$publicKey     = 'your-public-key';
$privateKey    = 'your-private-key';
$username      = 'your-username';
$password      = 'your-password';

$client = new Client(new \GuzzleHttp\Client(), $clientId);

$auth = new AuthenticationOperation(
    new RSACrypto($privateKey, $publicKey),
    $username,
    $password,
    $token
);

$response = $auth->execute($client);

$crypto = new AESCrypto($response['DataReturn']['AESKey'], $response['DataReturn']['AESIV']);

$transferOperation = new SendTransferOperation(
    $crypto,
    $response['DataReturn']['Token'],
    $bankAccountId,
    1,
    'Transferência XYZ',
    '735',
    'CC',
    '1',
    '12345',
    'José da Silva',
    '12345678910',
    55.0,
    new DateTime()
);

var_dump($transferOperation->execute($client));