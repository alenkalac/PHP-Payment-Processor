<?php

namespace App\Payments\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\CallbackInterface;

class CustomCache implements CacheInterface{
    /**
     * @var FilesystemTagAwareAdapter
     */
    private $cache;

    public function __construct() {
        $this->cache = new FilesystemTagAwareAdapter($_ENV['PAYPAL_SANDBOX'] ? "sandbox" : "");
    }

    public function getCache(): CacheInterface {
        return $this->cache;
    }

    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null) {
        return $this->cache->get($key, $callback, $beta, $metadata);
    }

    public function delete(string $key): bool {
        return $this->cache->delete($key);
    }
}