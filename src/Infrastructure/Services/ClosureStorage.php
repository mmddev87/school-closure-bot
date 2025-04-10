<?php

namespace Infrastructure\Services;

use Core\Interfaces\StorageInterface;
use DateTime;

class ClosureStorage implements StorageInterface
{
    protected string $path;
    protected array $data;

    public function __construct($directory, $fileName = 'closure')
    {
        date_default_timezone_set('Asia/Tehran');
        $this->path = $directory . '/' . $fileName . '.json';
        
        if (!file_exists($directory)) {
            mkdir($directory);
        }

        $this->data = file_exists($this->path) ? (json_decode(file_get_contents($this->path), true) ?? []) : [];
    }

    public function get(string $key): mixed
    {
        $now = new DateTime();
        $dataForLast24Hours = null;

        foreach ($this->data as $timestamp => $entry) {
            $entryTime = new DateTime($timestamp);
            $interval = $now->diff($entryTime);

            if ($interval->days == 0 && $interval->h < 24) {
                if (isset($entry[$key])) {
                    $dataForLast24Hours = $entry[$key];
                }
            }
        }

        return $dataForLast24Hours;
    }

    public function set(string $key, mixed $value): void
    {
        $timestamp = (new DateTime())->format('Y-m-d H:i:s');
        $this->data[$timestamp][$key] = $value;

        file_put_contents($this->path, json_encode(
            $this->data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
        ));
    }
}
