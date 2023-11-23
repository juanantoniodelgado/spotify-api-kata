<?php

namespace App\Infrastructure\Spotify\Endpoint;

use App\Infrastructure\Cache\CVMakerCacheClient;
use App\Infrastructure\Spotify\SpotifyAPIConnectionException;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

abstract class AbstractSpotifyEndpoint
{
    private const SPOTIFY_ACCESS_TOKEN_REDIS_KEY = 'spotify-access-token-redis-key';

    protected ClientInterface $apiClient;

    protected string $accessToken;

    /**
     * @throws SpotifyAPIConnectionException
     */
    public function __construct(
        #[Autowire(value: '%env(string:SPOTIFY_CLIENT_ID)%')]
        private readonly string $clientId,
        #[Autowire(value: '%env(string:SPOTIFY_CLIENT_SECRET)%')]
        private readonly string $clientSecret,
        private readonly CVMakerCacheClient $cache,
    ) {
        $this->apiClient = new Client([
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'timeout' => 2.0,
        ]);

        self::logIn();
    }

    /**
     * @throws SpotifyAPIConnectionException
     */
    private function logIn() : void
    {
        $cachedAccessToken = $this->cache->fetch(self::SPOTIFY_ACCESS_TOKEN_REDIS_KEY);

        if ($cachedAccessToken !== null) {
            $this->accessToken = $cachedAccessToken;
            return;
        }

        try {
            $response = $this->apiClient->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['access_token'])) {
                $this->accessToken = $responseBody['access_token'];

                $this->cache->save(
                    self::SPOTIFY_ACCESS_TOKEN_REDIS_KEY,
                    $responseBody['access_token'],
                    $responseBody['expires_in']
                );
            }
        } catch (ClientExceptionInterface $exception) {
            throw SpotifyAPIConnectionException::fromClientException($exception);
        }
    }
}
