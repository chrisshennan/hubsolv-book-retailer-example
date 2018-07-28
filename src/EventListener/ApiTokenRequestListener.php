<?php

namespace App\EventListener;

use App\Exception\InvalidApiToken;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ApiTokenRequestListener
{
    /**
     * @param GetResponseEvent $event
     * @throws InvalidApiToken
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $authHeader = $event->getRequest()->headers->get('API-TOKEN');

        if (!$authHeader) {
            throw new InvalidApiToken();
        }

        // Add some more logic
    }
}