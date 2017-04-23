<?php

namespace Sergo\L10n\Repositories;

use Sergo\L10n\Abstraction\AbstractRepository;
use Sergo\L10n\Config\Settings;
use Sergo\L10n\Interfaces\RepositoryInterface;

class FileRepository extends AbstractRepository implements RepositoryInterface
{




    /**
     * FileRepository constructor.
     *
     * @param string $files_path
     *
     * @throws \Exception
     */
    public function __construct($files_path)
    {
        $this->settings = Settings::getInstance()->getSettings();

        if (is_dir($files_path)) {
            $files_path = rtrim($files_path, '/') . '/';
            $locale_file = $files_path . $this->settings['locale'] . ".php";

            if (is_file($locale_file)) {
                $this->cache = require_once $locale_file; // TODO test this
            } else {
                throw new \Exception("File '{$locale_file}' not found");
            }
        } else {
            throw new \Exception("Path '{$files_path}' not found");
        }
    }
}
