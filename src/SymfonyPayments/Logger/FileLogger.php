<?php
namespace App\SymfonyPayments\Logger;

use Symfony\Component\HttpKernel\KernelInterface;

class FileLogger implements HyperLogger {

    private const LOGGER_NAME = "file";
    private $filename = "";
    private $app;

    public function __construct(KernelInterface $appKernel) {
        $this->filename = $_ENV["FILE_LOG_NAME"];
        $this->app = $appKernel;
    }

    public function getLoggerName(): string {
        return self::LOGGER_NAME;
    }

    public function log($message, $location = null) {
        file_put_contents($this->app->getLogDir() . "/" . $this->filename, "[ " . date("F j, Y, g:i a") . " ] " . $message . "\n", FILE_APPEND);
    }
}