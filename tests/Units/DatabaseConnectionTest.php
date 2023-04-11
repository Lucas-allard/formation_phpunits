<?php

namespace App\Units;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\MySQLiConnection;
use App\Database\PDOConnection;
use App\Exception\DatabaseConnexionException;
use App\Exception\MissingArgumentException;
use App\Exception\NotFoundException;
use App\Helpers\Config;
use mysqli;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{

    public function testItThrowMissingArgumentExceptionWithWrongCredentialKey()
    {

        self::expectException(MissingArgumentException::class);
        $credentials = [];
        $pdoHandler = new PDOConnection($credentials);


    }

    /**
     * @throws MissingArgumentException
     * @throws NotFoundException
     * @throws DatabaseConnexionException
     */
    public function testItCanConnectToDatabaseWithPdoApi(): DatabaseConnectionInterface|PDOConnection
    {

        $credentials = $this->getCredentials('pdo');
        $pdoHandler = (new PDOConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $pdoHandler);

        return $pdoHandler;
    }

    /**
     * @depends testItCanConnectToDatabaseWithPdoApi
     * @param DatabaseConnectionInterface $handler
     */
    public function testItIsAValidPdoConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(PDO::class, $handler->getConnection());
    }


    /**
     * @throws MissingArgumentException
     * @throws NotFoundException
     * @throws DatabaseConnexionException
     */
    public function testItCanConnectToDatabaseWithMysqliApi(): DatabaseConnectionInterface|PDOConnection
    {

        $credentials = $this->getCredentials('mysqli');
        $handler = (new MySQLiConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $handler);

        return $handler;
    }

    /**
     * @depends testItCanConnectToDatabaseWithMysqliApi
     * @param DatabaseConnectionInterface $handler
     */
    public function testItIsAValidMysqliConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(mysqli::class, $handler->getConnection());
    }

    /**
     * @throws NotFoundException
     */
    private function getCredentials(string $type): array
    {
        return array_merge(
            Config::get('database', $type),
            ['db_name' => 'bug_app_testing']
        );
    }

}