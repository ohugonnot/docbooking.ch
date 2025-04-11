<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DoctorSecurityController extends AbstractController
{
    /**
     * @Route("/doctor/login", name="app_doctor_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_doctor_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $class = 'account-page';

        return $this->render('security/doctor/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'classBody' => $class]);
    }

    /**
     * @Route("/doctor/logout", name="app_doctor_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
