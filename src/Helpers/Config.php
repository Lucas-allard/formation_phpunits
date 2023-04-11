<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Exception\NotFoundException;

class Config
{

    public static function get(string $filename, string $key = null)
    {
        $fileContent = self::getFileContent($filename);

        if ($key === null) {
            return $fileContent;
        }

        return $fileContent[$key] ?? [];

    }

    /**
     * @throws NotFoundException
     */
    public static function getFileContent(string $fileName): array
    {
        $fileContent = [];

        try {
            $filePath = realpath(sprintf(__DIR__ . '/../Configs/%s.php', $fileName));

            if (file_exists($filePath)) {
                $fileContent = require $filePath;
            }
        } catch (\Throwable $e) {
            throw new NotFoundException(
                sprintf('Error while reading config file %s', $fileName), [
                    'not found file', 'data is passed'
                ]
            );
        }


        return $fileContent;

    }
}