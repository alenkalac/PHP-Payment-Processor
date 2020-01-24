<?php
namespace App\SymfonyPayments\Logger;

interface HyperLogger {
    public function getLoggerName(): string;
    public function log($message, $location);
}