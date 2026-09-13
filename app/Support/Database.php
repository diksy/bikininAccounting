<?php

declare(strict_types=1);

namespace App\Support;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require dirname(__DIR__) . '/config.php';
        $db = $config['database'];

        $dsn = self::buildDsn($db['connection'], $db['database'], $db['host'], $db['port']);

        try {
            self::$connection = new PDO(
                $dsn,
                $db['username'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new \RuntimeException('Database connection failed: ' . $exception->getMessage(), 0, $exception);
        }

        return self::$connection;
    }

    private static function buildDsn(string $connection, string $database, string $host, int $port): string
    {
        $projectRoot = dirname(__DIR__, 2);

        return match ($connection) {
            'sqlite' => 'sqlite:' . $projectRoot . DIRECTORY_SEPARATOR . $database,
            'mariadb', 'mysql' => sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $host,
                $port,
                $database
            ),
            default => throw new \InvalidArgumentException('Unsupported DB connection: ' . $connection),
        };
    }
}
