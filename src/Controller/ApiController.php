<?php

namespace App\Controller;

use Exception;
use App\Entity\EmailNotify;
use App\Repository\EmailNotifyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api.')]
class ApiController extends AbstractController {

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response {
        return $this->render('swagger-ui.html.twig');
    }

    #[Route('/v1/hello', name: 'hello', methods: ['GET'])]
    public function hello(): JsonResponse {
        return $this->json("Hello World");
    }

    #[Route('/v1/email-notify', name: 'save_email_notify', methods: ['POST'])]
    public function saveEmailNotify(
        Request $request,
        EmailNotifyRepository $emailNotifyRepository
    ): Response {
        $email = json_decode($request->getContent(), true)['email'] ?? null;
        if (!$email) {
            return new JsonResponse(['status' => 'error', 'message' => 'Email manquant'], Response::HTTP_BAD_REQUEST);
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                if (!$emailNotifyRepository->findOneBy(['email' => $email])) {
                    $emailNotify = (new EmailNotify())->setEmail($email);
                    $this->em->persist($emailNotify);
                    $this->em->flush();
                }
                return new Response(null, Response::HTTP_CREATED);
            } catch (Exception $e) {
                return new JsonResponse(['status' => 'error', 'message' => "L'email n'a pas pu être enregistré en base de données"], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return new JsonResponse(['status' => 'error', 'message' => 'Email invalide'], Response::HTTP_BAD_REQUEST);
        }
    }
}
