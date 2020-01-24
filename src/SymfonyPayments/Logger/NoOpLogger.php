<?php
namespace App\SymfonyPayments\Logger;

class NoOpLogger implements HyperLogger {

    public function getLoggerName(): string {
        return "";
    }

    public function log($message = null, $location = null) {
        //DO NOTHING
    }
}