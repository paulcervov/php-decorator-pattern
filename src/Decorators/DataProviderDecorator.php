<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Contracts\DataProviderInterface;

/**
 * Class DataProviderDecorator
 * @package App\Decorators
 */
class DataProviderDecorator implements DataProviderInterface
{
    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * DataProviderDecorator constructor.
     * @param DataProviderInterface $dataProvider
     */
    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param string $url
     * @return array
     */
    public function get(string $url): array
    {
        return $this->dataProvider->get($url);
    }
}
