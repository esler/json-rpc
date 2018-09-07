<?php
use GuzzleHttp\Client;
use Esler\JsonRpc;

// get credentials
[$self, $user, $pswd] = $argv;
if (!$user || !$pswd) {
    die("Please provide credentials, run $self <user> <password>" . PHP_EOL);
}

require __DIR__ . '/../vendor/autoload.php';

// create Guzzle client with baseUrl pointing to JSON-RPC entrypoint
$client = new Client([
    'base_uri' => 'https://trac-hacks.org/login/rpc',
    'auth' => [$user, $pswd],
]);

// create client of JSON-RPC and give it configured Guzzle client
$rpc = new JsonRpc($client);

// call method "wiki.getAllPages()" and print the result
var_dump($rpc->wiki->getAllPages());
