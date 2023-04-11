<?php

namespace App\Units;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidArgumentLogLevelArgument;
use App\Helpers\App;
use App\Logger\Logger;
use App\Logger\LogLevel;
use Exception;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    private Logger $logger;

    protected function setUp(): void
    {
        $this->logger = new Logger();
        parent::setUp();
    }

    public function testItImplementLoggerInterface()
    {

        self::assertInstanceOf(
            LoggerInterface::class,
            $this->logger
        );
    }

    /**
     * @throws Exception
     */
    public function testItCanCreateDifferentTypesOfLogLevel()
    {
        $this->logger->emergency('Emergency message');
        $this->logger->alert('Alert message');
        $this->logger->log(LogLevel::WARNING, 'Warning message');

        $app = new App();

        $fileName = sprintf(
            "%s/%s-%s.log",
            $app->getLogPath(),
            'test',
            date('j.n.Y'),
        );

        $this->assertFileExists($fileName);

        $fileContent = file_get_contents($fileName);

        $this->assertStringContainsString('Emergency message', $fileContent);
        $this->assertStringContainsString('Alert message', $fileContent);
        $this->assertStringContainsString(LogLevel::WARNING, $fileContent);

        unlink($fileName);

        $this->assertFileNotExists($fileName);
    }

    /**
     * @throws Exception
     */
    public function testItThrowInvalidArgumentExceptionWhenInvalidLogLevelIsPassed()
    {
        self::expectException(InvalidArgumentLogLevelArgument::class);

        $this->logger->log('invalid log level', 'Invalid log level message');
    }
}