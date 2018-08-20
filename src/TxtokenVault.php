<?php

namespace Txtoken;

use Txtoken\Exceptions\TxtokenException;
use Txtoken\Validation\NumericValidator;
use Txtoken\Validation\JsonValidator;

class TxtokenVault
{
    /**
     * @var TxtokenApiClient
     */
    private $TxtokenClient;

    /**
     * @param TxtokenApiClient $TxtokenClient
     */
    public function __construct(TxtokenApiClient $TxtokenClient)
    {
        $this->TxtokenClient = $TxtokenClient;
    }


    /**
     * Create new Vault
     * @param string ownerId
     * @param string passPhrase
     * @param string type
     * @param Date expiry
     * @param JSON data
     * @return Array
     */
    public function createVault($data)
    {
        if (empty($data['ownerId'])) {
            throw new TxtokenException('Missing or invalid ownerId!');
        }
        if (empty($data['passPhrase'])) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }
        if (empty($data['type'])) {
            throw new TxtokenException('Missing or invalid type!');
        }
        if (empty($data['expiry'])) {
            throw new TxtokenException('Missing or invalid expiry date!');
        }
        if (!JsonValidator::validate($data['data'], true)) {
            throw new TxtokenException('Invalid data!');
        }

        $response = $this->TxtokenClient->post('/vault/create', $data);

        if ($response['status'] == 'ok') {
            $result['vaultId'] = $response['vaultId'];
        } else {
            $result = null;
        }

        return $result;
    }


    /**
     * Retrieve Vault
     * @param contractAddress
     * @param address
     * @return Array
     */
    public function getVault($data)
    {
        if (empty($data['vaultId'])) {
            throw new TxtokenException('Missing or invalid vaultId!');
        }
        if (empty($data['passPhrase'])) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }

        $response = $this->TxtokenClient->post('/vault/get', $data);

        if ($response['status'] == 'ok') {
            $result['ownerId'] = $response['ownerId'];
            $result['type'] = $response['type'];
            $result['expiry'] = $response['expiry'];
            $result['data'] = json_decode($response['data'], true);
        } else {
            $result = null;
        }

        return $result;
    }
}
