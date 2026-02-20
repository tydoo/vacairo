<?php

namespace App\Controller;

use App\Entity\Vacation;
use App\Form\VacationType;
use App\Repository\VacationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

#[Route('/vacations', name: 'vacations.', methods: ['GET', 'POST'])]
class VacationsController extends AbstractController {

    public function __construct(
        private readonly VacationRepository $vacationRepository,
        private readonly EntityManagerInterface $em,
        #[Target('vacations')] private readonly WorkflowInterface $vacationsWorkflow

    ) {
    }

    #[Route(name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request) {
        $vacationForm = $this->createForm(VacationType::class, new Vacation());
        $vacationForm->handleRequest($request);
        $this->createNew($vacationForm);

        return $this->render('vacations/list.html.twig', [
            'vacations' => $this->vacationRepository->findBy(
                [],
                ['date' => 'ASC']
            ),
            'vacationForm' => $vacationForm,
        ]);
    }

    #[Route('/stage/{id}/{state}', name: 'change_state', methods: ['POST'])]
    public function changeState(
        Vacation $vacation,
        string $state
    ): Response {
        $this->vacationsWorkflow->apply($vacation, $state);
        $this->em->flush();
        $referer = $this->container->get('request_stack')->getCurrentRequest()->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('home.home'));
    }

    public function createNew(FormInterface $form) {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            $referer = $this->container->get('request_stack')->getCurrentRequest()->headers->get('referer');
            return $this->redirect($referer ?? $this->generateUrl('home.home'));
        }
    }
}
