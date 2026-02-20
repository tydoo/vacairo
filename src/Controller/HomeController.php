<?php

namespace App\Controller;

use App\Entity\Vacation;
use App\Form\VacationType;
use App\Repository\VacationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController {

    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(): RedirectResponse {
        return $this->redirectToRoute('home.home');
    }

    #[Route('/home', name: 'home.home', methods: ['GET', 'POST'])]
    public function home(
        Request $request,
        VacationRepository $vacationRepository,
        VacationsController $vacationsController
    ): Response {
        $vacationForm = $this->createForm(VacationType::class, new Vacation());
        $vacationForm->handleRequest($request);
        $vacationsController->createNew($vacationForm);

        return $this->render('home.html.twig', [
            'vacations' => $vacationRepository->findTodayAndUpcomingVacations(),
            'vacationForm' => $vacationForm,
        ]);
    }

    #[Route('/onboarding', name: 'home.onboarding', methods: ['GET'])]
    public function onboarding(): Response {
        return $this->render('onboarding.html.twig');
    }
}
