<?php

declare(strict_types=1);

namespace App\Support;

final class Env
{
    private static array $data = [];

    public static function load(string $basePath): array
    {
        $path = $basePath . DIRECTORY_SEPARATOR . '.env';

        if (!is_file($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $trimmed, 2), 2, '');
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            self::$data[$key] = $value;
        }

        return self::$data;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$data[$key] ?? getenv($key) ?? $default;
    }
}
