<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Contracts\DataProviderInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CachingDataProviderDecorator
 * @package App\Decorators
 */
class CachingDataProviderDecorator extends DataProviderDecorator
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * CachingDataProviderDecorator constructor.
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache)
    {
        parent::__construct($dataProvider);
        $this->cache = $cache;
    }

    /**
     * @param string $url
     * @return array
     * @throws InvalidArgumentException
     */
    public function get(string $url): array
    {
        $cacheKey = $this->getCacheKeyFromUrl($url);

        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = parent::get($url);

        $cacheItem
            ->set($result);

        $this->cache->save($cacheItem);

        return $result;

    }

    /**
     * @param string $url
     * @return string
     */
    private function getCacheKeyFromUrl(string $url): string
    {
        return base64_encode($url);
    }
}
