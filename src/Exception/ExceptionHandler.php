<?php

declare(strict_types=1);

namespace App\Exception;


use App\Helpers\App;
use ErrorException;
use Throwable;

class ExceptionHandler
{

    public static function handle(Throwable $exception): void
    {
        $app = new App();

        if ($app->isDebugMode()) {
            var_dump($exception);
        } else {
            echo 'Something went wrong';
        }
        exit();
    }

    /**
     * @throws ErrorException
     */
    public static function convertWarningsAndNoticesToException($severity, $message, $file, $line): void
    {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }

}