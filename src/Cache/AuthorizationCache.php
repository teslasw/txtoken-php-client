<?php

namespace Txtoken\Cache;

use Txtoken\Validation\JsonValidator;

abstract class AuthorizationCache
{
    public static $CACHE_PATH = __DIR__ . '/auth.cache';

    /**
     * A pull method which would read the persisted data based on clientId.
     * If clientId is not provided, an array with all the tokens would be passed.
     *
     * @param array|null $config
     * @param string $clientId
     * @return mixed|null
     */
    public static function pull($apiId = null)
    {

        $tokens = null;
        $cachePath = self::$CACHE_PATH;

        if (file_exists($cachePath)) {
            // Read from the file
            $cachedToken = file_get_contents($cachePath);

            if ($cachedToken && JsonValidator::validate($cachedToken, true)) {
                $tokens = json_decode($cachedToken, true);
                if ($apiId && is_array($tokens) && array_key_exists($apiId, $tokens)) {
                    // If client Id is found, just send in that data only
                    return $tokens[$apiId];
                } elseif ($apiId) {
                    // If client Id is provided, but no key in persisted data found matching it.
                    return null;
                }
            }
        }
        return $tokens;
    }

    /**
     * Persists the data into a cache file provided in $CACHE_PATH
     *
     * @param array|null $config
     * @param      $clientId
     * @param      $accessToken
     * @param      $tokenCreateTime
     * @param      $tokenExpiresIn
     * @throws \Exception
     */
    public static function push($clientId, $accessToken, $tokenCreateTime, $tokenExpiresIn)
    {

        $cachePath = self::$CACHE_PATH;
        if (!is_dir(dirname($cachePath))) {
            if (mkdir(dirname($cachePath), 0755, true) == false) {
                throw new \Exception("Failed to create directory at $cachePath");
            }
        }

        // Reads all the existing persisted data
        $tokens = self::pull();
        $tokens = $tokens ? $tokens : array();
        if (is_array($tokens)) {
            $tokens[$clientId] = array(
                'apiId' => $clientId,
                'accessToken' => $accessToken,
                'tokenCreateTime' => $tokenCreateTime,
                'tokenExpiresIn' => $tokenExpiresIn
            );
        }
        if (!file_put_contents($cachePath, json_encode($tokens))) {
            throw new \Exception("Failed to write cache");
        };
    }

    
}