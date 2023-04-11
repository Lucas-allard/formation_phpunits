<?php

namespace App\Units;

use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
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
     */
    public function testItCanConnectToDatabaseWithPdoApi()
    {

        $credentials = [
            'driver' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'test',

        ];
        $pdoHandler = (new PDOConnection($credentials))->connect();
        self::assertNotNull($pdoHandler);

    }

}