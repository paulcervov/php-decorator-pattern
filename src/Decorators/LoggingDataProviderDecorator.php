<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Contracts\DataProviderInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class LoggingDataProviderDecorator
 * @package App\Decorators
 */
class LoggingDataProviderDecorator extends DataProviderDecorator {

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggingDataProviderDecorator constructor.
     * @param DataProviderInterface $dataProvider
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $dataProvider, LoggerInterface $logger)
    {
        parent::__construct($dataProvider);
        $this->logger = $logger;
    }

    /**
     * @param string $url
     * @return array
     */
    public function get(string $url): array
    {
        try {
            return parent::get($url);
        } catch (Throwable $t) {
            $this->logger->error($t);
        }

        return [];
    }
}
