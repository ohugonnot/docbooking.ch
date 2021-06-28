<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpClient\HttpClient;

class DoctorSecurityController extends AbstractController
{
    /**
     * @Route("/doctor/login", name="app_doctor_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
		/*$client = HttpClient::create();
		$response = $client->request('GET', 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
		$resp_json = $response->getContent();
		$json_data = json_decode($resp_json);
		
		if(!in_array($json_data->countryCode, ['CH', 'TN'])){
			$this->addFlash('doctor_registration_error', 'your country is not allowed to register!');
		}*/
		
		if ($this->getUser()) {
			return $this->redirectToRoute('app_doctor_dashboard');
		}
		
		//if (in_array($json_data->countryCode, ['CH', 'TN']) ) {
			// get the login error if there is one
			$error = $authenticationUtils->getLastAuthenticationError();
			// last username entered by the user
			$lastUsername = $authenticationUtils->getLastUsername();
		//}
		
		$class='account-page';

        return $this->render('security/doctor/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'classBody' => $class]);
    }

    /**
     * @Route("/doctor/logout", name="app_doctor_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
