<?php

namespace App\Domain\Album;

use InvalidArgumentException;

final readonly class AlbumUri
{
    private function __construct(
        public string $platform,
        public string $uriType,
        public string $externalId,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromUri(string $externalUri) : self
    {
        $separatedUri = explode(':', $externalUri);

        if (count($separatedUri) !== 3) {
            throw new InvalidArgumentException(sprintf('Invalid Album URI "%s"', $externalUri));
        }

        return new self($separatedUri[0], $separatedUri[1], $separatedUri[2]);
    }

    public function toUri() : string
    {
        return implode(':', [$this->platform, $this->uriType, $this->externalId]);
    }
}
