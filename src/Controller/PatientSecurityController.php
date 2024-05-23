<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PatientSecurityController extends AbstractController
{
    /**
     * @Route("/patient/login", name="app_patient_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $redirect_to = $request->query->get('redirect_to');
        if ($this->getUser()) {
            if ($redirect_to) {
                return $this->redirect($redirect_to);
            } else {
                return $this->redirectToRoute('app_patient_dashboard');
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $class = 'account-page';

        return $this->render(
            'security/patient/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'classBody' => $class,
                'redirect_to' => $redirect_to
            ]
        );
    }

    /**
     * @Route("/patient/logout", name="app_patient_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
