<?php
declare(strict_types=1);

namespace App\Services\Config;

use Exception;

final class FileNotExistsException extends Exception
{
    public function __construct(?string $filename) {
        $this->message = "Specified file doesn't exist: " . $filename;
    }
}
