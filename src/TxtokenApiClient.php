<?php

namespace Txtoken;

use Txtoken\Exceptions\TxtokenApiException;
use Txtoken\Exceptions\TxtokenException;
use Txtoken\Cache\AuthorizationCache;

/**
 * Class TxtokenClient
 */
class TxtokenApiClient
{
    /**
     * Txtoken API Token
     * @var string
     */
    private $accessToken = '';

    /**
     * Txtoken API Id
     * @var string
     */
    private $apiId;

    /**
     * Txtoken API Secret
     * @var string
     */
    private $apiSecret;

    /**
     * Txtoken API Mode live or sandbox
     * @var boolean
     */
    private $liveMode = false;

    private $lastResponseRaw;

    private $lastResponse;

    public $url = '';

    const USER_AGENT = 'Txtoken PHP API SDK 0.1';

    /**
     * Maximum amount of time in seconds that is allowed to make the connection to the API server
     * @var int
     */
    public $curlConnectTimeout = 60;

    /**
     * Maximum amount of time in seconds to which the execution of cURL call will be limited
     * @var int
     */
    public $curlTimeout = 60;

    /**
     * @param string $accessToken Txtoken Store API Token
     * @throws \Txtoken\Exceptions\TxtokenException if the library failed to initialize
     */
    public function __construct($apiId, $apiSecret, $liveMode)
    {
        if (strlen($apiId) < 5 && !isset($apiSecret)) {
            throw new TxtokenException('Missing or invalid API credentials!');
        }

        $this->url = ($liveMode) ? 'https://ushan.io/' : 'https://sandbox.ushan.io/';
        //$this->url = ($liveMode) ? 'https://ushan.io/' : 'http://localhost:4000/';
        $this->apiId = $apiId;
        $this->apiSecret = $apiSecret;
        $this->getAccessToken();
    }


    public function getAccessToken()
    {
        // Check for persisted data first
        $token = AuthorizationCache::pull($this->apiId);
        if ($token) {

            // if cached found
            $tokenExpiresIn = $token['tokenExpiresIn'];
            $this->accessToken = $token['accessToken'];

            // if expired
            if (time() > $tokenExpiresIn) {
                // get new token from API
                try {
                    $data = ['api_id'=>$this->apiId, 'api_secret'=>$this->apiSecret];
                    $res = $this->post('/api-account/signin', $data);

                    $tokenCreateTime = time();
                    $tokenExpiresIn = strtotime('+1 hour');
                    $this->accessToken = $res['token'];

                    AuthorizationCache::push($this->apiId, $this->accessToken, $tokenCreateTime, $tokenExpiresIn);

                } catch (TxtokenApiException $e) {
                    // API response status code was not successful
                    echo 'Txtoken API Exception: ' . $e->getCode() . ' ' . $e->getMessage();
                } catch (TxtokenException $e) {
                    // API call failed
                    echo 'Txtoken Exception: ' . $e->getMessage();
                }
            }

        } else {
            // lets create one, if not found
            try {
                $data = ['api_id'=>$this->apiId, 'api_secret'=>$this->apiSecret];
                $res = $this->post('/api-account/signin', $data);

                $tokenCreateTime = time();
                $tokenExpiresIn = strtotime('+1 hour');
                $this->accessToken = $res['token'];

                AuthorizationCache::push($this->apiId, $this->accessToken, $tokenCreateTime, $tokenExpiresIn);

            } catch (TxtokenApiException $e) {
                // API response status code was not successful
                echo 'Txtoken API Exception: ' . $e->getCode() . ' ' . $e->getMessage();
            } catch (TxtokenException $e) {
                // API call failed
                echo 'Txtoken Exception: ' . $e->getMessage();
            }
        }

        return $this->accessToken;
    }

    /**
     * Perform a GET request to the API
     * @param string $path Request path (e.g. 'orders' or 'orders/123')
     * @param array $params Additional GET parameters as an associative array
     * @return mixed API response
     * @throws \Txtoken\Exceptions\TxtokenApiException if the API call status code is not in the 2xx range
     * @throws TxtokenException if the API call has failed or the response is invalid
     */
    public function get($path, $params = [], $isTypeJson = true)
    {
        return $this->request('GET', $path, $params, $isTypeJson);
    }

    /**
     * Perform a DELETE request to the API
     * @param string $path Request path (e.g. 'orders' or 'orders/123')
     * @param array $params Additional GET parameters as an associative array
     * @return mixed API response
     * @throws \Txtoken\Exceptions\TxtokenApiException if the API call status code is not in the 2xx range
     * @throws \Txtoken\Exceptions\TxtokenException if the API call has failed or the response is invalid
     */
    public function delete($path, $params = [], $isTypeJson = true)
    {
        return $this->request('DELETE', $path, $params, $isTypeJson);
    }

    /**
     * Perform a POST request to the API
     * @param string $path Request path (e.g. 'orders' or 'orders/123')
     * @param array $data Request body data as an associative array
     * @param array $params Additional GET parameters as an associative array
     * @return mixed API response
     * @throws \Txtoken\Exceptions\TxtokenApiException if the API call status code is not in the 2xx range
     * @throws TxtokenException if the API call has failed or the response is invalid
     */
    public function post($path, $data = [], $isTypeJson = true, $params = [])
    {
        return $this->request('POST', $path, $data, $isTypeJson, $params);
    }

    /**
     * Perform a PUT request to the API
     * @param string $path Request path (e.g. 'orders' or 'orders/123')
     * @param array $data Request body data as an associative array
     * @param array $params Additional GET parameters as an associative array
     * @return mixed API response
     * @throws \Txtoken\Exceptions\TxtokenApiException if the API call status code is not in the 2xx range
     * @throws \Txtoken\Exceptions\TxtokenException if the API call has failed or the response is invalid
     */
    public function put($path, $data = [], $isTypeJson = true, $params = [])
    {
        return $this->request('PUT', $path, $data, $isTypeJson, $params);
    }

    /**
     * Return raw response data from the last request
     * @return string|null Response data
     */
    public function getLastResponseRaw()
    {
        return $this->lastResponseRaw;
    }

    /**
     * Return decoded response data from the last request
     * @return array|null Response data
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Internal request implementation
     * @param string $method POST, GET, etc.
     * @param string $path
     * @param array $params
     * @param mixed $data
     * @return
     * @throws \Txtoken\Exceptions\TxtokenApiException
     * @throws \Txtoken\Exceptions\TxtokenException
     */
    private function request($method, $path, $data = null, $isTypeJson = true, $params = [])
    {
        $this->lastResponseRaw = null;
        $this->lastResponse = null;

        $url = trim($path, '/');

        // if (!empty($params)) {
        //     $url .= '?' . http_build_query($params);
        // }

        $curl = curl_init($this->url . $url);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        //curl_setopt($curl, CURLOPT_ENCODING, true);

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeout);

        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);

        if ($data !== null) {

            if ($isTypeJson) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json', 
                    'Content-Length: ' . strlen(json_encode($data)),
                    'Authorization: Bearer ' . $this->accessToken)
                );
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $this->accessToken)
                );
            }
        }

        $this->lastResponseRaw = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errorNumber = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($errorNumber) {
            throw new TxtokenException('CURL: ' . $error, $errorNumber);
        }

        $this->lastResponse = $response = json_decode($this->lastResponseRaw, true);

        // if (!isset($response['code'], $response['result'])) {
        //     $e = new TxtokenException('Invalid API response');
        //     $e->rawResponse = $this->lastResponseRaw;
        //     throw $e;
        // }
        $status = $httpCode; //(int)$response['code'];
        if ($status < 200 || $status >= 300) {
            $e = new TxtokenApiException((string)$response['error'], $status);
            $e->rawResponse = $this->lastResponseRaw;
            throw $e;
        }

        return $response;
    }
}