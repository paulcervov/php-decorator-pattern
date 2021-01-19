<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Decorators\CachingDataProviderDecorator;
use App\Decorators\LoggingDataProviderDecorator;
use App\Providers\ApiDataProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$apiProvider = new ApiDataProvider($client);

$cache = new FilesystemAdapter('app', 60, 'var/cache');
$apiProvider = new CachingDataProviderDecorator($apiProvider, $cache);

$logger = new Logger('name', [new StreamHandler(__DIR__ . '/var/logs/app.log', Logger::ERROR)]);
$apiProvider = new LoggingDataProviderDecorator($apiProvider, $logger);

$data = $apiProvider->get('https://dog.ceo/api/breeds/image/random');
print_r($data);
