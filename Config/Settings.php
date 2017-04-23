<?php

namespace Sergo\L10n\Config;

class Settings
{
    private static $instance = null;

    protected $settings = [];

    /**
     * @return self
     */
    public static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function setSettings($settings) {
        if (is_array($settings)) {
            $this->settings = $settings;
        }
    }

    public function getSettings() {
        return $this->settings;
    }

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
}
