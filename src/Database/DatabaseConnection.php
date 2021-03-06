<?php

namespace _404\Database;

use Anax\Common\ConfigureInterface;
use Anax\Common\ConfigureTrait;
use PDO;

/**
 * Class DatabaseConnection
 */
class DatabaseConnection implements ConfigureInterface
{
    use ConfigureTrait;

    private $pdo;

    public function connect()
    {
        try {
            $this->pdo = new \PDO(...$this->config);
        } catch (\Exception $e) {
            // Rethrow to hide connection details, through the original
            // exception to view all connection details.
            // throw $e;
            throw new \PDOException("Could not connect to database.");
        }

        return $this;
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
