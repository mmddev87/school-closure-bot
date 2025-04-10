<?php

namespace Core\Interfaces;

use DateTime;

interface StorageInterface {
    function get(string $key): mixed;
    function set(string $key, mixed $value): void;
}