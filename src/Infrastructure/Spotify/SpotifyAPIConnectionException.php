<?php

namespace App\Infrastructure\Spotify;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;

class SpotifyAPIConnectionException extends Exception
{
    public static function fromClientException(ClientExceptionInterface $exception) : self
    {
        return new self($exception->getMessage());
    }
}
