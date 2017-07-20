<?php

namespace Neon\PjApi\Operations;

use Neon\PjApi\Client;
use Neon\PjApi\Crypto\Crypto;

class GetAccountsOperation extends AbstractOperation
{

    protected $token;
    protected $crypto;

    public function __construct(Crypto $crypto, $token)
    {
        $this->crypto = $crypto;
        $this->token = $token;
    }

    public function execute(Client $client)
    {
        $params = json_encode([
            'ClientID' => $client->getClientId(),
        ]);

        try {
            $response = $client->post('/SubAccount/GetAccounts', [
                'headers' => ['Token' => $this->token],
                'form_params' => ['Data' => $this->crypto->encrypt($params)],
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
        }

        return json_decode($this->crypto->decrypt($response['Data']), true);
    }

}