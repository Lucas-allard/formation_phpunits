<?php

namespace App\Units;

use App\Helpers\App;
use Exception;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{

    public function testItCanGetAnInstanceOfTheApplication()
    {
        $this->assertInstanceOf(
            App::class,
            new App()
        );
    }

    /**
     * @throws Exception
     */
    public function testItCanGetBasicDatasetFromAppClass()
    {
        $app = new App();


        self::assertTrue($app->isRunningInConsole());
        self::assertSame('test', $app->getEnv());
        self::assertNotNull($app->getLogPath());
        $this->assertInstanceOf(\DateTime::class, $app->getServerTime());
    }
}