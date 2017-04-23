<?php

namespace Sergo\L10n\Interfaces;

/**
 * @property array $data
 */
interface RepositoryInterface
{
    /**
     * @param string $key
     *
     * @return array|string
     */
    public function get($key);

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getFew(array $keys);
}
