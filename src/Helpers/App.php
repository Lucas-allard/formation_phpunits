<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;

class App
{

    private mixed $config = [];

    public function __construct()
    {
        $this->config = Config::get('app');
    }

    public function isDebugMode(): bool
    {
        return $this->config['debug'] ?? false;
    }

    public function getEnv(): string
    {
        return $this->isTestMode() ? 'test' : $this->config['env'] ?? 'production';
    }


    public function getAppName(): string
    {
        return $this->config['app_name'] ?? 'Bug report App';
    }

    public function getLogPath(): string
    {
        return $this->config['log_path'] ?? throw new \RuntimeException('Log path is not defined');
    }

    public function isRunningInConsole(): bool
    {
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * @throws Exception
     */
    public function getServerTime(): \DateTimeInterface
    {
        return new \DateTime("now", new \DateTimeZone('Europe/Berlin'));
    }

    public function isTestMode(): bool
    {
        if ($this->isRunningInConsole() && defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING) {
            return true;
        }
        return false;
    }
}