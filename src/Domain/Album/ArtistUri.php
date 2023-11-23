<?php

namespace App\Domain\Album;

use App\Domain\AbstractPlatformUri;
use InvalidArgumentException;

class ArtistUri extends AbstractPlatformUri
{
    /**
     * @throws InvalidArgumentException
     */
    public static function fromUri(string $externalUri) : self
    {
        $separatedUri = explode(':', $externalUri);

        if (count($separatedUri) !== 3) {
            throw new InvalidArgumentException(sprintf('Invalid Artist URI "%s"', $externalUri));
        }

        return new self($separatedUri[0], $separatedUri[1], $separatedUri[2]);
    }
}
