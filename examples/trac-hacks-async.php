<?php
use GuzzleHttp\Client;
use Esler\JsonRpc;
use function GuzzleHttp\Promise\all;

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

$promises = [];
// call three different methods id the same time
$promises[] = $rpc->request('wiki.getAllPages')->then('\var_dump');
$promises[] = $rpc->request('system.listMethods')->then('\var_dump');
$promises[] = $rpc->request('system.getAPIVersion')->then('\var_dump');

// wait for all promises will complete, fail when some fails
all($promises)->wait();
