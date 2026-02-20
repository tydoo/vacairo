<?php

namespace App\Security;

use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class ProConnectProvider extends AbstractProvider {
    use BearerAuthorizationTrait;

    private const ALGO_PROCONNECT_USER_INFO = 'RS256';

    /**
     * URL de base pour ProConnect
     */
    protected string $baseUrl;
    public function __construct(array $options = [], array $collaborators = []) {
        parent::__construct($options, $collaborators);

        // Récupérer l'URL de base depuis les options ou utiliser la valeur par défaut
        $this->baseUrl = $options['base_url'];
    }
    /**
     * Get authorization url to begin OAuth flow
     */
    public function getBaseAuthorizationUrl(): string {
        return $this->baseUrl . '/api/v2/authorize';
    }

    /**
     * Get access token url to retrieve token
     */
    public function getBaseAccessTokenUrl(array $params): string {
        return $this->baseUrl . '/api/v2/token';
    }

    /**
     * Get provider url to fetch user details
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string {
        return $this->baseUrl . '/api/v2/userinfo';
    }

    public function getBaseEndSessionUrl(): string {
        return $this->baseUrl . '/api/v2/session/end';
    }

    /**
     * Get the default scopes used by this provider.
     */
    protected function getDefaultScopes(): array {
        return ['openid'];
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $data['error_description'] ?? $data['error'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function fetchResourceOwnerDetails(AccessToken $token) {
        $url = $this->getResourceOwnerDetailsUrl($token);

        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $url, $token);

        $response = $this->getParsedResponse($request);

        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($response);
            assert($token instanceof UnencryptedToken);

            if ($token->headers()->get('alg') !== self::ALGO_PROCONNECT_USER_INFO) {
                throw new UnsupportedHeaderFound('Invalid token algorithm');
            }

            return $token->claims()->all();
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            throw $e;
        }
    }

    /**
     * Generate a user object from a successful user details request.
     */
    protected function createResourceOwner(array $claims, AccessToken $token): ProConnectResourceOwner {
        return new ProConnectResourceOwner($claims);
    }
}
