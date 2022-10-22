<?php
namespace App\Payments\Logger;

interface HyperLogger {
    public function getLoggerName(): string;
    public function log($message, $location);
}