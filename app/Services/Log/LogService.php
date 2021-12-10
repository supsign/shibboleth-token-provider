<?php

namespace App\Services\Log;

use DateTime;
use Illuminate\Support\Facades\Storage;

class LogService
{
    protected string $level = 'info';
    protected string $message;
    protected mixed $properties;

    public function __construct()
    {
        $this->filePath = '/log/activityLog.csv';
    }

    public function log(string $message): self
    {
        $this->message = $message;

        return $this->writeLog();
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function withProperties(mixed $properties): self
    {
        $this->properties = json_encode($properties);

        return $this;
    }

    protected function writeLog()
    {
        $body = implode(';', [
            (new DateTime)->format('Y-m-d H:i:s'),
            $this->level,
            $this->message,
            $this->properties
        ]);

        Storage::append($this->filePath, $body, "\r\n");

        return $this;
    }
}