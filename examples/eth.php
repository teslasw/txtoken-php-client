<?php
use Txtoken\Exceptions\TxtokenApiException;
use Txtoken\Exceptions\TxtokenException;
use Txtoken\TxtokenApiClient;
use Txtoken\TxtokenEth;

require_once '../vendor/autoload.php';

// Replace this with your API key
$apiId = 'txcore';
$apiSecret = 'sW268heX#dL';


try {
    $client = new TxtokenApiClient($apiId, $apiSecret, false);
    $eth = new TxtokenEth($client);

    // Create Account
    // $data = ['ownerId'=>'3', 'passPhrase'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'];
    // $res = $eth->createAccount($data);

    // Send Transaction
    $data = ['toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
        'amount'=>'1',
        'tokenId'=>'5b4ee3c236272c198ce9eb72',
        'passPhrase'=>'dog_cat_rat',
        'gasLimit'=>'210000',
        'gasPrice'=>'low',
        'isCheckFee'=>'yes'
    ];
    $res = $eth->sendTransaction($data);

    // Send Custom Transaction
    // $data = ['toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
    //     'amount'=>'1',
    //     'tokenId'=>'5b4ee3c236272c198ce9eb72',
    //     'passPhrase'=>'dog_cat_rat',
    //     'gasLimit'=>'210000',
    //     'gasPrice'=>'low',
    //     'contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf',
    //     'contractAbi'=>'[{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"spender","type":"address"},{"name":"tokens","type":"uint256"}],"name":"approve","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"from","type":"address"},{"name":"to","type":"address"},{"name":"tokens","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"tokenOwner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"acceptOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"a","type":"uint256"},{"name":"b","type":"uint256"}],"name":"safeSub","outputs":[{"name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":false,"inputs":[{"name":"to","type":"address"},{"name":"tokens","type":"uint256"}],"name":"transfer","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"a","type":"uint256"},{"name":"b","type":"uint256"}],"name":"safeDiv","outputs":[{"name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":false,"inputs":[{"name":"spender","type":"address"},{"name":"tokens","type":"uint256"},{"name":"data","type":"bytes"}],"name":"approveAndCall","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"a","type":"uint256"},{"name":"b","type":"uint256"}],"name":"safeMul","outputs":[{"name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":true,"inputs":[],"name":"newOwner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"tokenAddress","type":"address"},{"name":"tokens","type":"uint256"}],"name":"transferAnyERC20Token","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"tokenOwner","type":"address"},{"name":"spender","type":"address"}],"name":"allowance","outputs":[{"name":"remaining","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"a","type":"uint256"},{"name":"b","type":"uint256"}],"name":"safeAdd","outputs":[{"name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":false,"inputs":[{"name":"_newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"payable":true,"stateMutability":"payable","type":"fallback"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_from","type":"address"},{"indexed":true,"name":"_to","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"from","type":"address"},{"indexed":true,"name":"to","type":"address"},{"indexed":false,"name":"tokens","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"tokenOwner","type":"address"},{"indexed":true,"name":"spender","type":"address"},{"indexed":false,"name":"tokens","type":"uint256"}],"name":"Approval","type":"event"}]',
    //     'isCheckFee'=>'yes'
    // ];
    // $res = $eth->sendCustomTransaction($data);

// Send Custom Transaction with Etherscan
// $data = ['toAddress'=>'0xaB678F28E8b5bFC2590c5aF0b9dDe4429750eC1c', 
//     'amount'=>'1.5',
//     'tokenId'=>'5b4ee3c236272c198ce9eb72',
//     'passPhrase'=>'dog_cat_rat',
//     'gasLimit'=>'210000',
//     'gasPrice'=>'low',
//     'contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf',
//     'isCheckFee'=>'no'
// ];
// $res = $eth->sendCustomTransactionEtherscan($data);


    // Get Balance
    // $res = $eth->getBalance(array('address'=>'0xa79BbE0e5f01388DF28a15Ac930Fa61481206bf5'));

    // Get Custom Balance
    // $res = $eth->getCustomBalance(array('contractAddress'=>'0xA25c3545A9858Ef57e75Fc9def3d506f469c7Bbf', 'address'=>'0xa79BbE0e5f01388DF28a15Ac930Fa61481206bf5'));

    print_r($res);

} catch (TxtokenApiException $e) {
    // API response status code was not successful
    echo 'Txtoken API Exception: ' . $e->getCode() . ' ' . $e->getMessage();
} catch (TxtokenException $e) {
    // API call failed
    echo 'Txtoken Exception: ' . $e->getMessage();
    var_export($client->getLastResponseRaw());
}







