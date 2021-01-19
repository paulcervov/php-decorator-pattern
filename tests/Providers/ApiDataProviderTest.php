<?php

declare(strict_types=1);

namespace App\Tests\Providers;

use App\Decorators\CachingDataProviderDecorator;
use App\Decorators\LoggingDataProviderDecorator;
use App\Providers\ApiDataProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;

class ApiDataProviderTest extends TestCase
{
    public function testGet()
    {

        $client = HttpClient::create();

        $apiProvider = new ApiDataProvider($client);

        $cache = new FilesystemAdapter('app', 60, 'var/cache');
        $apiProvider = new CachingDataProviderDecorator($apiProvider, $cache);

        $logger = new Logger('name', [new StreamHandler('var/logs/app.log', Logger::ERROR)]);
        $apiProvider = new LoggingDataProviderDecorator($apiProvider, $logger);

        $data = $apiProvider->get('https://dog.ceo/api/breeds/image/random');
        $dataFromCache = $apiProvider->get('https://dog.ceo/api/breeds/image/random');
        $this->assertEquals(json_encode($data), json_encode($dataFromCache));

        $data = $apiProvider->get('https://wrong_url');
        $this->assertEmpty($data);
        $this->assertFileExists('var/logs/app.log');
    }
}
