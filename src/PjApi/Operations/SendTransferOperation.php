<?php

namespace Neon\PjApi\Operations;

use Neon\PjApi\Client;
use Neon\PjApi\Crypto\Crypto;

class SendTransferOperation extends AbstractOperation
{

    protected $bankAccountId;
    protected $finalityId;
    protected $description;
    protected $codBank;
    protected $accountType;
    protected $branch;
    protected $bankAccountNumber;
    protected $name;
    protected $document;
    protected $value;
    protected $schedulingDate;

    protected $token;
    protected $crypto;

    public function __construct(Crypto $crypto, $token, $bankAccountId, $finalityId, $description, $codBank, $accountType,
                                $branch, $bankAccountNumber, $name, $document,
                                $value, \DateTime $schedulingDate)
    {
        $this->crypto = $crypto;
        $this->token = $token;
        $this->bankAccountId = (int)$bankAccountId;
        $this->finalityId = (int)$finalityId;
        $this->description = (string)$description;
        $this->codBank = (string)$codBank;
        $this->accountType = (string)$accountType;
        $this->branch = (string)$branch;
        $this->bankAccountNumber = (string)$bankAccountNumber;
        $this->name = (string)$name;
        $this->document = (string)$document;
        $this->value = (float)$value;
        $this->schedulingDate = $schedulingDate;
    }

    public function execute(Client $client)
    {
        $params = json_encode([
            'BankAccountId' => $this->bankAccountId,
            'FinalityId'    => $this->finalityId,
            'Description'   => $this->description,
            'CreditTo'      => [

                'CodBank'           => $this->codBank,
                'AccountyType'      => $this->accountType,
                'Branch'            => $this->branch,
                'BankAccountNumber' => $this->bankAccountNumber,
                'Name'              => $this->name,
                'Document'          => $this->document,
                'Value'             => $this->value,
                'SchedulingDate'    => $this->schedulingDate->format('Y-m-d\TH:i:s'),

            ],
            'ClientID'      => $client->getClientId(),
        ]);

        $this->request = $params;

        try {
            $response = $client->post('/Transfer/SendTransfer', [
                'headers' => ['Token' => $this->token],
                'form_params' => ['Data' => $this->crypto->encrypt($params)],
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
        }

        return json_decode($this->crypto->decrypt($response['Data']), true);
    }

}