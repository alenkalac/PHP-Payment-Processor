<?php
namespace App\Payments\Logger;

use Symfony\Component\HttpKernel\KernelInterface;

class EnvAwareLogger {
    private $loggers;
    private $noOpLogger;

    public function __construct(KernelInterface $appKernel) {
        $this->loggers[] = $this->noOpLogger = new NoOpLogger();
        $this->loggers[] = new FileLogger($appKernel);
        $this->loggers[] = new DiscordLogger();
    }

    public function getLogger(): HyperLogger {
        $envLogger = $_ENV["LOGGER"];

        /** @var HyperLogger $logger */
        foreach($this->loggers as $logger) {
            if($logger->getLoggerName() == $envLogger) {
                return $logger;
            }
        }

        return $this->noOpLogger;
    }
}