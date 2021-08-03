<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminSwitchListener implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private RouterInterface $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onRedirectSwitchAdmin',
            'kernel.exception' => 'onAccessDenied',
        ];
    }

    public function onRedirectSwitchAdmin(RequestEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (!$token)
            return;
        $roles = $token->getRoleNames();
        $firewall = $event->getRequest()->attributes->get('_firewall_context');
        if ($roles && in_array('ROLE_ADMIN', $roles) && $firewall && !str_contains($firewall, '.admin')) {
            $response = new RedirectResponse($this->router->generate('app_admin_dashboard'));
            $event->setResponse($response);
        }
    }

    public function onAccessDenied(ExceptionEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (!$token)
            return;
        $firewall = $event->getRequest()->attributes->get('_firewall_context');
        $roles = $token->getRoleNames();
        if ($roles && in_array('ROLE_PATIENT', $roles) && $firewall && (str_contains($firewall, '.admin') || str_contains($firewall, '.doctor'))) {
            $response = new RedirectResponse($this->router->generate('app_patient_dashboard'));
            $event->setResponse($response);
        }
        if ($roles && in_array('ROLE_DOCTOR', $roles) && $firewall && (str_contains($firewall, '.admin') || str_contains($firewall, '.patient'))) {
            $response = new RedirectResponse($this->router->generate('app_doctor_dashboard'));
            $event->setResponse($response);
        }
    }
}
