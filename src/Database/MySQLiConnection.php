<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\DatabaseConnexionException;
use mysqli;
use mysqli_driver;
use mysqli_sql_exception;
use Throwable;

class MySQLiConnection extends AbstractConnection implements DatabaseConnectionInterface
{


    const REQUIRED_CONNECTION_KEYS = [
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch',
    ];

    protected function parseCredentials(array $credentials): array
    {
        return [
            $credentials['host'],
            $credentials['db_username'],
            $credentials['db_user_password'],
            $credentials['db_name'],
        ];
    }

    /**
     * @throws DatabaseConnexionException
     */
    public function connect(): static
    {
        $driver = new mysqli_driver;
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

        $credentials = $this->parseCredentials($this->credentials);

        try {
            $this->connection = new mysqli(...$credentials);
            $this->connection->set_charset('utf8mb4');
            $this->connection->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        } catch (Throwable $e) {
            throw new DatabaseConnexionException($e->getMessage(), $this->credentials, 500);
        }

        return $this;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}