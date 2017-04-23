<?php

namespace Sergo\L10n;

use Sergo\L10n\Config\Settings;
use Sergo\L10n\Repositories\FileRepository;
use Sergo\L10n\Repositories\MysqlRepository;

// TODO implement parse "this.ololo"
class L10n
{

    /**
     * @param array
     */
    protected $allowed_repositories = ['mysql', 'files'];

    /**
     * @var array
     */
    protected $default_settings = [
        'repository' => 'mysql',
        'locale' => 'uk',
        'separator' => '.', // actual only for FILES repository
        'var_separator_left' => '{',
        'var_separator_right' => '}',
    ];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected static $cache = [];

    protected $repository;

    /**
     * @param array $settings
     *
     * @throws \Exception
     */
    public function __construct(array $settings) {
        $this->settings = array_merge($this->default_settings, $settings);
        Settings::getInstance()->setSettings($this->settings);

        $repository = $this->settings['repository'];

        if ($this->isCorrectRepository($repository)) {
            switch (true) {
                case ($repository === 'mysql'): {
                    $this->repository = new MysqlRepository($this->settings['credentials'], $this->settings['table_name']);
                } break;

                case ($repository === 'files'): {
                    $this->repository = new FileRepository($this->settings['files_path']);
                } break;
            }
        } else {
            throw new \Exception("Undefined repository");
        }
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return array|string
     */
    public function get($key, $params = array()) {
        $result = $this->repository->get($key);

        return (!empty($params) && is_array($params)) ? $this->parseParams($result, $params) : $result;
    }

    /**
     * @param array $keys
     * @param array $params
     *
     * @return array
     */
    public function getFew(array $keys, $params = array()) {
        $result = $this->repository->getFew($keys);

        foreach($result as $key => &$value) {
            if (isset($params[$key])) {
                $value = $this->parseParams($value, $params[$key]);
            }
        }

        return $result;
    }

    /**
     * @param string $string
     * @param array $params
     *
     * @return mixed
     */
    public function parseParams($string, $params) {
        $from = array_keys($params);
        $from = array_map(function($val) {
            return $this->settings['var_separator_left'] . $val . $this->settings['var_separator_right'];
        }, $from);

        $to = array_values($params);

        return str_replace($from, $to, $string);
    }

    /**
     * @param $repository
     *
     * @return bool
     */
    protected function isCorrectRepository($repository) {
        return in_array($repository, $this->allowed_repositories);
    }
}
