<?php

namespace App\Database;

use App\Exception\MissingArgumentException;

abstract class AbstractConnection
{

    protected $connection;
    protected array $credentials;

    const REQUIRED_CONNECTION_KEYS = [];

    /**
     * @throws MissingArgumentException
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        if (!$this->credentialsHaveRequiredKeys($this->credentials)) {
            throw new MissingArgumentException(
                sprintf('Database connection credentials are not mapped correctly, required keys are: %s.', implode(', ', self::REQUIRED_CONNECTION_KEYS))
            );
        }
    }

    private function credentialsHaveRequiredKeys(array $credentials): bool
    {

        return count(array_intersect(static::REQUIRED_CONNECTION_KEYS, array_keys($this->credentials))) === count(static::REQUIRED_CONNECTION_KEYS);
    }
}