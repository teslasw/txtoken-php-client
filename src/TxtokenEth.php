<?php

namespace Txtoken;

use Txtoken\Exceptions\TxtokenException;
use Txtoken\Validation\NumericValidator;

class TxtokenEth
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
     * Create new ETH account
     * @param Array
     * @return Array
     */
    public function createAccount($data)
    {
        if (!isset($data['ownerId'])) {
            throw new TxtokenException('Missing or invalid ownerId!');
        }

        if (  strlen($data['passPhrase']) < 32 ) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }

        $response = $this->TxtokenClient->post('/eth/create-account', $data);

        if ($response['status'] == 'ok') {
            $result['publicAddress'] = $response['publicAddress'];
            $result['tokenId'] = $response['tokenId'];
        } else {
            $result = null;
        }

        return $result;
    }


    /**
     * Send ETH transaction
     * @param toAddress
     * @param amount
     * @param tokenId
     * @param passPhrase
     * @param gasLimit
     * @param gasPrice
     * @param String isCheckFee (yes|no)
     * @return Array
     */
    public function sendTransaction($data)
    {
        if (!isset($data['toAddress'])) {
            throw new TxtokenException('Missing or invalid toAddress!');
        }
        if (trim($data['amount']) != null && !is_numeric($data['amount'])) {
            throw new TxtokenException('Missing or invalid amount!');
        }
        if (!isset($data['tokenId'])) {
            throw new TxtokenException('Missing or invalid tokenId!');
        }
        if (empty($data['passPhrase'])) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }
        if (filter_var($data['gasLimit'], FILTER_VALIDATE_INT) === false) {
            throw new TxtokenException('Missing or invalid gasLimit!');
        }
        if (empty($data['gasPrice']) || is_numeric($data['gasPrice'])) {
            throw new TxtokenException('Missing or invalid gasPrice!');
        }
        if (!isset($data['isCheckFee'])) {
            throw new TxtokenException('Missing or invalid isCheckFee!');
        }


        $response = $this->TxtokenClient->post('/eth/send-transaction', $data);

        if ($response['status'] == 'ok') {
            if ($data['isCheckFee'] != 'yes') {
                $result['transaction'] = $response['transaction'];
            }
            $result['txFee'] = $response['txFee'];
        } else {
            $result = null;
        }

        return $result;
    }


    /**
     * Send Custom token transaction
     * @param toAddress
     * @param amount
     * @param tokenId
     * @param passPhrase
     * @param gasLimit
     * @param gasPrice
     * @param contractAddress
     * @param String (JSON Array format) contractAbi
     * @param String isCheckFee (yes|no)
     * @return Array
     */
    public function sendCustomTransaction($data)
    {
        if (!isset($data['toAddress'])) {
            throw new TxtokenException('Missing or invalid toAddress!');
        }
        if (trim($data['amount']) != null && !is_numeric($data['amount'])) {
            throw new TxtokenException('Missing or invalid amount!');
        }
        if (!isset($data['tokenId'])) {
            throw new TxtokenException('Missing or invalid tokenId!');
        }
        if (empty($data['passPhrase'])) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }
        if (filter_var($data['gasLimit'], FILTER_VALIDATE_INT) === false) {
            throw new TxtokenException('Missing or invalid gasLimit!');
        }
        if (empty($data['gasPrice']) || is_numeric($data['gasPrice'])) {
            throw new TxtokenException('Missing or invalid gasPrice!');
        }
        if (!isset($data['contractAddress'])) {
            throw new TxtokenException('Missing or invalid contractAddress!');
        }
        if (!isset($data['contractAbi'])) {
            throw new TxtokenException('Missing or invalid contractAbi!');
        }
        if (!isset($data['isCheckFee'])) {
            throw new TxtokenException('Missing or invalid isCheckFee!');
        }


        $response = $this->TxtokenClient->post('/eth/send-custom-transaction', $data, false);

        if ($response['status'] == 'ok') {
            if ($data['isCheckFee'] != 'yes') {
                $result['transaction'] = $response['transaction'];
            }
            $result['txFee'] = $response['txFee'];
        } else {
            $result = null;
        }

        return $result;
    }


    /**
     * Send Custom token transaction, bet contract ABI from Etherscan API
     * @param toAddress
     * @param amount
     * @param tokenId
     * @param passPhrase
     * @param gasLimit
     * @param gasPrice
     * @param contractAddress
     * @param String isCheckFee (yes|no)
     * @return Array
     */
    public function sendCustomTransactionEtherscan($data)
    {
        if (!isset($data['toAddress'])) {
            throw new TxtokenException('Missing or invalid toAddress!');
        }
        if (trim($data['amount']) != null && !is_numeric($data['amount'])) {
            throw new TxtokenException('Missing or invalid amount!');
        }
        if (!isset($data['tokenId'])) {
            throw new TxtokenException('Missing or invalid tokenId!');
        }
        if (empty($data['passPhrase'])) {
            throw new TxtokenException('Missing or invalid passPhrase!');
        }
        if (filter_var($data['gasLimit'], FILTER_VALIDATE_INT) === false) {
            throw new TxtokenException('Missing or invalid gasLimit!');
        }
        if (empty($data['gasPrice']) || is_numeric($data['gasPrice'])) {
            throw new TxtokenException('Missing or invalid gasPrice!');
        }
        if (!isset($data['contractAddress'])) {
            throw new TxtokenException('Missing or invalid contractAddress!');
        }
        if (!isset($data['isCheckFee'])) {
            throw new TxtokenException('Missing or invalid isCheckFee!');
        }


        $response = $this->TxtokenClient->post('/eth/send-custom-transaction-etherscan', $data);

        if ($response['status'] == 'ok') {
            if ($data['isCheckFee'] != 'yes') {
                $result['transaction'] = $response['transaction'];
            }
            $result['txFee'] = $response['txFee'];
        } else {
            $result = null;
        }

        return $result;
    }


    /**
     * Retrieve account balance
     * @param address
     * @return Balance in Ether
     */
    public function getBalance($data)
    {
        $response = $this->TxtokenClient->post('/eth/get-balance', $data);

        if ($response['status'] == 'ok') {
            $bal = $response['balance'];
        } else {
            $bal = null;
        }

        return $bal;
    }


    /**
     * Retrieve custom token account balance
     * @param contractAddress
     * @param address
     * @return Array
     */
    public function getCustomBalance($data)
    {
        $response = $this->TxtokenClient->post('/eth/get-custom-balance', $data);

        if ($response['status'] == 'ok') {
            $bal = $response['balance'];
        } else {
            $bal = null;
        }

        return $bal;
    }
}
