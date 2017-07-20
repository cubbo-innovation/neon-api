<?php

namespace Neon\PjApi\Operations;

use Neon\PjApi\Client;
use Neon\PjApi\Crypto\Crypto;

class AuthenticationOperation extends AbstractOperation
{

    protected $crypto;
    protected $username;
    protected $password;
    protected $token;

    public function __construct(Crypto $crypto, $username, $password, $token)
    {
        $this->crypto = $crypto;
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
    }

    public function execute(Client $client)
    {
        $data = json_encode([
            'Username' => $this->username,
            'Password' => $this->password,
            'RequestDate' => date('Y-m-d\TH:i:s'),
        ]);

        $data = $this->crypto->encrypt($data);

        $response = $client->post('/Client/Authentication', [
            'headers' => ['Token' => $this->token],
            'form_params' => ['Data' => $data]
        ]);

        return json_decode($this->crypto->decrypt($response['Data']), true);
    }

}