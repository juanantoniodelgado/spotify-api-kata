<?php

namespace App\Infrastructure\Cache;

use Psr\Log\LoggerInterface;
use Redis;
use RedisException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CVMakerCacheClient
{
    private Redis $redisClient;

    public function __construct(
        private readonly LoggerInterface $logger,
        #[Autowire(value: '%env(string:REDIS_URL)%')]
        string $redisUrl,
    ) {
        $this->redisClient = RedisAdapter::createConnection($redisUrl);
    }

    public function fetch(string $key) : ?string
    {
        try {
            $retrievedValue = $this->redisClient->get($key);

            if ($retrievedValue !== false && strlen($retrievedValue) > 0) {
                return $retrievedValue;
            }
        } catch (RedisException $exception) {
            $this->logger->warning(sprintf('Unexpected Redis exception when retrieving "%s"', $key), [
                $exception->getCode(),
                $exception->getMessage(),
            ]);
        }

        return null;
    }

    public function save(string $key, string $value, int $expirationTime) : void
    {
        try {
            $this->redisClient->set($key, $value, $expirationTime);
        } catch (RedisException $exception) {
            $this->logger->warning(sprintf('Unexpected Redis exception when saving "%s"', $key), [
                $exception->getCode(),
                $exception->getMessage(),
            ]);
        }
    }
}
