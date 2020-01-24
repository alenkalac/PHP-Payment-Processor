<?php
namespace App\SymfonyPayments\Logger;

use DiscordWebhooks\Client;
use DiscordWebhooks\Embed;

class DiscordLogger implements HyperLogger {
    private const LOGGER_NAME = "discord";

    public function log($message, $channel) {
        try {
            $dClient = new Client($channel);

            $embed = new Embed();
            $embed->description($message);

            $embed->color(3911681);

            $dClient->embed($embed)->send();
        }
        catch (\Exception $e) {

        }
    }

    public function getLoggerName(): string {
        return self::LOGGER_NAME;
    }
}