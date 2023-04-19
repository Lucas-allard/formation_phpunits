<?php

namespace App\Helpers;

use App\Database\MySQLiConnection;
use App\Database\PDOConnection;
use App\Database\QueryBuilder;
use App\Exception\DatabaseConnexionException;
use App\Exception\MissingArgumentException;
use App\Exception\NotFoundException;
use App\Database\MySQLiQueryBuilder;
use App\Database\PDOQueryBuilder;

class DbQueryBuilderFactory
{

    /**
     * @throws NotFoundException
     * @throws MissingArgumentException
     * @throws DatabaseConnexionException
     */
    public static function make(
        string $crendentialFile = 'database',
        string $connectionType = 'pdo',
        array  $options = []
    ): QueryBuilder
    {
        $credentials = array_merge(
            Config::get($crendentialFile, $connectionType),
            $options
        );

        switch ($connectionType) {
            case 'pdo':
                $connection = (new PDOConnection($credentials))->connect();
                return new PDOQueryBuilder($connection);
                break;
            case 'mysqli':
                $connection = (new MySQLiConnection($credentials))->connect();
                return new MySQLiQueryBuilder($connection);
                break;
            default:
                throw new NotFoundException('Connection type not found', ['type' => $connectionType]);
        }
    }
}