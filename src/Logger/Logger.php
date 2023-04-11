<?php

namespace App\Logger;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidArgumentLogLevelArgument;
use App\Helpers\App;
use Exception;

class Logger implements LoggerInterface
{

    /**
     * @throws Exception
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function alert(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::ALERT, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function critical(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function error(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::ERROR, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function warning(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::WARNING, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function notice(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::NOTICE, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::INFO, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function debug(string $message, array $context = []): void
    {
        $this->addRecord(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function log(string $level, string $message, array $context = []): void
    {

        if (!in_array($level, LogLevel::getLevels())) {
            throw new InvalidArgumentLogLevelArgument($level, LogLevel::getLevels());
        }
        $this->addRecord($level, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function addRecord(string $level, string $message, array $context = []): void
    {
        $app = new App();

        $date = $app->getServerTime()->format('Y-m-d H:i:s');

        $env = $app->getEnv();

        $logPath = $app->getLogPath();

        $details = sprintf(
            "[%s] - Level: %s - Message: %s - Context: %s",
            $date,
            $level,
            $message,
            json_encode($context),
        ).PHP_EOL;

        $log = sprintf(
            "%s/%s-%s.log",
            $logPath,
            $env,
            date('j.n.Y'),
        );

        file_put_contents($log, $details, FILE_APPEND);
    }
}