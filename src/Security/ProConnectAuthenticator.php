<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ProConnectAuthenticator extends OAuth2Authenticator {
    private const ALGO_PROCONNECT_ID_TOKEN = 'RS256';

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function supports(Request $request): ?bool {
        return
            $request->attributes->get('_route') === 'security.login.sso.redirect' &&
            $request->isMethod('GET') &&
            $request->query->has('code') &&
            $request->query->has('state') &&
            $request->query->get('state') === $request->getSession()->get('knpu.oauth2_client_state');
    }

    public function authenticate(Request $request): Passport {
        $client = $this->clientRegistry->getClient('proconnect');
        $accessToken = $this->fetchAccessToken($client);
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($accessToken->getValues()['id_token']);
            assert($token instanceof UnencryptedToken);

            if ($token->headers()->get('alg') !== self::ALGO_PROCONNECT_ID_TOKEN) {
                $this->clearSessionNonceAndState($request);
                throw new UnsupportedHeaderFound('Invalid token algorithm');
            }

            if ($token->claims()->get('nonce') !== $request->getSession()->get('knpu.oauth2_client_nonce')) {
                $this->clearSessionNonceAndState($request);
                throw new RuntimeException('Invalid nonce');
            }
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            $this->clearSessionNonceAndState($request);
            throw $e;
        }

        $this->clearSessionNonceAndState($request);
        $request->getSession()->set('proconnect_id_token', $accessToken->getValues()['id_token']);
        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                $proconnectUser = $client->fetchUserFromToken($accessToken);

                $existingUser = $this->userRepository->findOneBy(['uuid' => $proconnectUser->getId()]);

                if ($existingUser) {
                    $existingUser->setLastLoggedAt(new DateTimeImmutable());
                    $this->em->flush();
                    return $existingUser;
                }

                $user = (new User())->setUuid($proconnectUser->getId());
                $this->em->persist($user);
                $this->em->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        /** @var User $user */
        $user = $token->getUser();
        return new RedirectResponse($this->urlGenerator->generate($user->isOnboarded() ? 'home.home' : 'home.onboarding'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
        return new RedirectResponse(
            $this->urlGenerator->generate('security.login')
        );
    }

    private function clearSessionNonceAndState(Request $request): void {
        $request->getSession()->remove('knpu.oauth2_client_state');
        $request->getSession()->remove('knpu.oauth2_client_nonce');
    }
}
