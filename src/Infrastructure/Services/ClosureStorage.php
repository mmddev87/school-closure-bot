<?php

namespace Infrastructure\Services;

use Core\Interfaces\StorageInterface;
use DateTime;
use Morilog\Jalali\Jalalian;

class ClosureStorage implements StorageInterface
{
    protected string $path;
    protected array $data;

    protected bool $changed = false;

    public function __construct($directory, $fileName = 'closure')
    {
        $this->path = $directory . '/' . $fileName . '.json';
        
        if (!file_exists($directory)) {
            mkdir($directory);
        }

        $this->data = file_exists($this->path) ? (json_decode(file_get_contents($this->path), true) ?? []) : [];
    }

    public function get(string $key): mixed
    {
        return $this->data[$this->getTodayDateString()][$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$this->getTodayDateString()][$key] = $value;

        file_put_contents($this->path, json_encode(
            $this->data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
        ));
    }

    public function deletePreviousData(DateTime $upTo): void
    {
        // TODO
    }

    private function getTodayDateString(): string {
        return jdate('today')->toDateString();
    }
}
