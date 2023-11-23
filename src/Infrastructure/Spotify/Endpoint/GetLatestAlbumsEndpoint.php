<?php

namespace App\Infrastructure\Spotify\Endpoint;

use App\Infrastructure\Spotify\Item\Album\Album;
use App\Infrastructure\Spotify\SpotifyAPIConnectionException;
use Psr\Http\Client\ClientExceptionInterface;

final class GetLatestAlbumsEndpoint extends AbstractSpotifyEndpoint
{
    /**
     * @return list<Album>
     *
     * @throws SpotifyAPIConnectionException
     */
    public function get(int $offset, int $limit) : array
    {
        try {
            $response = $this->apiClient->get('https://api.spotify.com/v1/browse/new-releases', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->accessToken),
                ],
                'query' => [
                    'limit' => (string) $limit,
                    'offset' => (string) $offset,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            $albums = [];
            foreach ($responseData['albums']['items'] as $album) {
                $albums[] = Album::fromSpotifyAPIResponse($album);
            }

            return $albums;
        } catch (ClientExceptionInterface $exception) {
            throw SpotifyAPIConnectionException::fromClientException($exception);
        }
    }
}
