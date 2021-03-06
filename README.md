# Txtoken REST API SDK for PHP

Simple PHP wrapper class for work with Txtoken API.

# Prerequisites
- PHP 5.3 or above
- curl, json extensions must be enabled

# Installation

1. Download Composer if not already installed
2. Go to your project directory. If you do not have one, just create a directory and cd in.
```sh
$ mkdir project
$ cd project
```
3. Execute composer require "teslasw/txtoken-php-client:*" on command line. Replace composer with composer.phar if required. It should show something like this:
```sh
$ composer require teslasw/txtoken-php-client:*

# output:
./composer.json has been created
Loading composer repositories with package information
Updating dependencies (including require-dev)
- Installing teslasw/txtoken-php-client (0.1): Downloading (100%) 
Loading from cache

Writing lock file
Generating autoload files
```

# Instructions
1. Autoload the SDK Package. This will include all the files and classes to your autoloader.
```sh
<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
require __DIR__  . '/vendor/autoload.php';
```
2. Provide your ApiId and ApiSecret, and ApiMode
```sh
// After Step 1
$apiId = 'xxxxxxxxxx';
$apiSecret = 'xxxxxxxxxx';
$apiMode = false; // true=Live mode, false=Sandbox mode
$client = new TxtokenApiClient($apiId, $apiSecret, $apiMode);
```
3. Lets make a call
```sh
// After Step 2
$eth = new TxtokenEth($client); 

// ========== ETH ==========
// Create Account
$data = array('ownerId'=>'xxxxx', 'passPhrase'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$res = $eth->createAccount($data);


// Send Transaction
$data = array('toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
     'amount'=>'1',
     'tokenId'=>'5b4ee3c236272c198ce9eb72',
     'passPhrase'=>'dog_cat_rat',
     'gasLimit'=>'21000',
     'gasPrice'=>'low',
     'isCheckFee'=>'yes'
);
$res = $eth->sendTransaction($data);


// Send Custom Transaction
$data = array('toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
    'amount'=>'1',
    'tokenId'=>'5b4ee3c236272c198ce9eb72',
    'passPhrase'=>'dog_cat_rat',
    'gasLimit'=>'210000',
    'gasPrice'=>'low',
    'contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf',
    'contractAbi'=>'[JSON Array]',
    'isCheckFee'=>'yes'
);
$res = $eth->sendCustomTransaction($data);


// Send Custom Transaction with Etherscan
$data = array('toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
    'amount'=>'1.5',
    'tokenId'=>'5b4ee3c236272c198ce9eb72',
    'passPhrase'=>'dog_cat_rat',
    'gasLimit'=>'210000',
    'gasPrice'=>'low',
    'contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf',
    'isCheckFee'=>'no'
);
$res = $eth->sendCustomTransactionEtherscan($data);


// Get Balance
$res = $eth->getBalance(array('address'=>'0xa79BbE0e5f01388DF28a15Ac930Fa61481206bf5'));


// Get Custom Balance
$res = $eth->getCustomBalance(array('contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf', 'address'=>'0xa79BbE0e5f01388DF28a15Ac930Fa61481206bf5'));


// ========== Vault ==========
$vault = new TxtokenVault($client);

// Create new Vault
$data = ['ownerId'=>'3', 'passPhrase'=>'dog_cat_rat', 'type'=>'bank', 'expiry'=>'2019-05-28', 'data'=>'{"bankAccount":"5682365248", "bankName":"K-Bank", "address":"12/2 Sukumvit 69"}'];
$res = $vault->createVault($data);


// Get vault
$data = ['vaultId'=>'5b7a369657669f1b7596dabc', 'passPhrase'=>'dog_cat_rat'];
$res = $vault->getVault($data);
    
    
```

3. All together
```sh
<?php

use Txtoken\Exceptions\TxtokenApiException;
use Txtoken\Exceptions\TxtokenException;
use Txtoken\TxtokenApiClient;
use Txtoken\TxtokenEth;

require_once '../vendor/autoload.php';


// Replace this with your API information
$apiId = 'xxxxxxxxxx';
$apiSecret = 'xxxxxxxxxx';
$apiMode = false; // true=Live mode, false=Sandbox mode

try {
    $client = new TxtokenApiClient($apiId, $apiSecret, $apiMode);
    $eth = new TxtokenEth($client);

    // Get Balance
    $res = $eth->getBalance(array('address'=>'0xa79BbE0e5f01388DF28a15Ac930Fa61481206bf5'));

    print_r($res);

} catch (TxtokenApiException $e) {
    // API response status code was not successful
    echo 'Txtoken API Exception: ' . $e->getCode() . ' ' . $e->getMessage();
} catch (TxtokenException $e) {
    // API call failed
    echo 'Txtoken Exception: ' . $e->getMessage();
    var_export($client->getLastResponseRaw());
}


```
