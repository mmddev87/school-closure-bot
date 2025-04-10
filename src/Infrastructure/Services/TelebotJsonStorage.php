<?php

namespace Infrastructure\Services;

use WeStacks\TeleBot\Storage\JsonStorage;
use WeStacks\TeleBot\TeleBot;

class TelebotJsonStorage extends JsonStorage
{
    protected string $path;

    protected array $data;

    protected bool $changed = false;

    public function __construct(TeleBot $bot)
    {
        $directory = BASE_PATH . '/storage';
        $fileName = 'telebot';
        $this->path = $directory . '/' . $fileName . '.json';

        if (!file_exists($directory)) {
            mkdir($directory);
        }

        $this->data = file_exists($this->path) ? (json_decode(file_get_contents($this->path), true) ?? []) : [];
    }

    public function __destruct()
    {
        if (! $this->changed) {
            return;
        }

        if (empty($this->data)) {
            return @unlink($this->path);
        }

        file_put_contents($this->path, json_encode($this->data));
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function set(string $key, $value): bool
    {
        $this->data[$key] = $value;

        return $this->changed = true;
    }

    public function delete(string $key): bool
    {
        unset($this->data[$key]);

        return $this->changed = true;
    }
}
