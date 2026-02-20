<?php

namespace App\Security;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ProConnectResourceOwner implements ResourceOwnerInterface {
    protected array $claims;

    public function __construct(array $claims) {
        $this->claims = $claims;
    }

    /**
     * Returns the identifier of the authorized resource owner.
     */
    public function getId(): ?string {
        return $this->claims['sub'] ?? null;
    }

    /**
     * Return all of the owner details available as an array.
     */
    public function toArray(): array {
        return $this->claims;
    }
}
