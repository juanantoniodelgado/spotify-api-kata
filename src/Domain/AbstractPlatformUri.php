<?php

namespace App\Domain;

abstract class AbstractPlatformUri
{
    protected function __construct(
        public string $platform,
        public string $uriType,
        public string $externalId,
    ) {
    }

    abstract public static function fromUri(string $externalUri) : self;

    public function toUri() : string
    {
        return implode(':', [$this->platform, $this->uriType, $this->externalId]);
    }
}
