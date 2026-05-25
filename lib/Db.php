<?php
final class Db
{
    public static function pdo(array $config): PDO
    {
        $dbConfig = $config['database'] ?? [];
        $path = PROJECT_ROOT . '/' . ($dbConfig['path'] ?? 'database/murakami.sqlite');
        $schema = PROJECT_ROOT . '/' . ($dbConfig['schema'] ?? 'database/schema.sql');

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $isNew = !file_exists($path);
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON;');
        $pdo->exec('PRAGMA journal_mode = WAL;');

        if ($isNew && is_file($schema)) {
            $sql = file_get_contents($schema);
            if ($sql !== false) {
                $pdo->exec($sql);
            }
        }

        return $pdo;
    }
}
