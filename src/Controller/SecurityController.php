<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SecurityController extends AbstractController {

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route('/login', name: 'security.login', methods: ['GET'])]
    public function index(): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home.home');
        }
        return $this->render('security/login.html.twig');
    }

    #[Route('/login/sso', name: 'security.login.sso', methods: ['POST'])]
    public function ssoLogin(Request $request, ClientRegistry $clientRegistry): RedirectResponse {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home.home');
        }
        $nonce = bin2hex(random_bytes(16));
        $response = $clientRegistry->getClient('proconnect')->redirect(['openid'], ['nonce' => $nonce]);

        $request->getSession()->set('knpu.oauth2_client_nonce', $nonce);

        return $response;
    }

    #[Route('/login/sso/redirect', name: 'security.login.sso.redirect', methods: ['GET'])]
    public function ssoLoginRedirect(Request $request): RedirectResponse {
        if ($request->query->get('state') !== $request->getSession()->get('knpu.oauth2_client_state')) {
            $this->addFlash('warning', 'Échec de la validation CSRF lors de la connexion avec ProConnect.');
        }

        if ($request->query->has('error')) {
            $this->addFlash('error', 'Une erreur est survenue lors de la connexion avec ProConnect : ' . $request->query->get('error_description', 'Erreur inconnue'));
        }

        $request->getSession()->remove('knpu.oauth2_client_state');
        $request->getSession()->remove('knpu.oauth2_client_nonce');

        return $this->redirectToRoute('security.login');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/logout', name: 'security.logout', methods: ['GET'])]
    public function ssoLogout(Request $request, ClientRegistry $clientRegistry): RedirectResponse {
        /** @var ProConnectProvider $provider */
        $provider = $clientRegistry->getClient('proconnect')->getOAuth2Provider();

        $state = bin2hex(random_bytes(16));
        $session = $request->getSession();
        $session->set('knpu.oauth2_client_state', $state);

        $url = $provider->getBaseEndSessionUrl();
        $url .= '?id_token_hint=' . $session->get('proconnect_id_token');
        $url .= '&state=' . $state;
        $url .= '&post_logout_redirect_uri=' . $this->urlGenerator->generate('security.logout.sso.redirect', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return new RedirectResponse($url);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/logout/sso/redirect', name: 'security.logout.sso.redirect', methods: ['GET'])]
    public function ssoLogoutRedirect(Request $request): RedirectResponse {
        if ($request->query->get('state') !== $request->getSession()->get('knpu.oauth2_client_state')) {
            $this->addFlash('warning', 'Échec de la validation CSRF lors de la déconnexion avec ProConnect.');
            return $this->redirectToRoute('home.home');
        }

        $request->getSession()->clear();

        return $this->redirect('/logout/symfony');
    }
}
