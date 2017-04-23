<?php

namespace Sergo\L10n\Abstraction;

abstract class AbstractRepository
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @param $key
     *
     * @return array|string
     */
    public function get($key)
    {
        if (false !== strpos($key, $this->settings['separator'])) {
            $data = explode($this->settings['separator'], $key);

            $result = $this->cache;
            foreach ($data as $val) {
                $result = $result[$val];
            }
        } else {
            $result = isset($this->cache[$key]) ? $this->cache[$key] : "";
        }

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getFew(array $keys)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }
}
