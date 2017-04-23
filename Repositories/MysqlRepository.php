<?php

namespace Sergo\L10n\Repositories;

use Sergo\L10n\Abstraction\AbstractRepository;
use Sergo\L10n\Config\Settings;
use Sergo\L10n\Interfaces\RepositoryInterface;

class MysqlRepository extends AbstractRepository implements RepositoryInterface
{




    /**
     * @var \PDO
     */
    protected $db;

    /**
     * MysqlRepository constructor.
     *
     * @param array $credentials
     * @param string $table_name
     *
     * @throws \Exception
     */
    public function __construct(array $credentials, $table_name)
    {
        $this->settings = Settings::getInstance()->getSettings();
        $this->registerDataBase($credentials);

        // TODO use prepared statements
        $data =$this->db->query("SELECT `data` FROM `{$table_name}` WHERE locale = '{$this->settings['locale']}';")->fetchAll();
        $this->cache = $data;
    }

    /**
     * @param array $credentials
     *
     * @return \PDO
     */
    protected function registerDataBase(array $credentials) {
        if (!$this->db) {
            $dsn = "{mysql:host={$credentials['host']};dbname={$credentials['database']};charset={$credentials['charset']}";
            $db = new \PDO($dsn, $credentials['username'], $credentials['password'], [\PDO::FETCH_ASSOC => true]);
            $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->db = $db;
        }

        return $this->db;
    }
}
