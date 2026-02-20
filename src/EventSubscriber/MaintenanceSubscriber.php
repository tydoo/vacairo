<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface {

    public function __construct(
        private readonly bool $maintenanceMode,
        private readonly Environment $twig
    ) {
    }

    public function onRequestEvent(RequestEvent $event): void {

        if ($this->maintenanceMode && !str_starts_with(
            $event->getRequest()->attributes->get('_route'),
            'api'
        )) {
            $response = new Response(
                $this->twig->render('maintenance.html.twig'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
