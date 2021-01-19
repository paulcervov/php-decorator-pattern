<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface DataProviderInterface
 * @package App\Contracts
 */
interface DataProviderInterface {

    /**
     * @param string $url
     * @return array
     */
    public function get(string $url): array;
}
