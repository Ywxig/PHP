<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Хранение записей в JSON-файле.
 */
final class JsonStorage
{
    /**
     * @param string $path Путь к файлу.
     */
    public function __construct(private string $path)
    {
    }

    /**
     * Возвращает все записи.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        if (!is_file($this->path)) {
            return [];
        }
        $decoded = json_decode((string) file_get_contents($this->path), true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Добавляет запись (с блокировкой файла).
     *
     * @param array<string, mixed> $record Запись.
     * @return void
     * @throws RuntimeException Если файл не удалось открыть.
     */
    public function add(array $record): void
    {
        $fp = fopen($this->path, 'c+');
        if ($fp === false) {
            throw new RuntimeException('Не удалось открыть файл данных.');
        }
        flock($fp, LOCK_EX);
        $decoded = json_decode((string) stream_get_contents($fp), true);
        $records = is_array($decoded) ? $decoded : [];
        $records[] = $record;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
