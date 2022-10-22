<?php
namespace App\Payments\Logger;

class NoOpLogger implements HyperLogger {

    public function getLoggerName(): string {
        return "";
    }

    public function log($message = null, $location = null) {
        //DO NOTHING
    }
}