<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorRegistrationFormType;
use App\Security\DoctorAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpClient\HttpClient;


class DoctorRegistrationController extends AbstractController
{
    /**
     * @Route("/doctor/register", name="app_doctor_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, DoctorAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
		$client = HttpClient::create();
		$response = $client->request('GET', 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
		$resp_json = $response->getContent();
		$json_data = json_decode($resp_json);
		
		if(!in_array($json_data->countryCode, ['CH','FR'/*, 'TN'*/])){
			$this->addFlash('doctor_registration_error', 'your country is not allowed to register!');
		}
		
		if ($this->getUser()) {
			return $this->redirectToRoute('app_doctor_dashboard');
		}
		
        $user = new Doctor();
        $form = $this->createForm(DoctorRegistrationFormType::class, $user);
        $form->handleRequest($request);
		
		$class='account-page';


        if ($form->isSubmitted() && $form->isValid() && in_array($json_data->countryCode, ['CH','FR'/*, 'TN'*/]) ) {
			try{
				// encode the plain password
				$link = $user->getUrlProfile();
				$dql   = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.slug LIKE '%$link%'";
				$em = $this->getDoctrine()->getManager();
				$query = $em->createQuery($dql)->getOneOrNullResult();
				$link = ($query['NumHits'] > 0) ? ($link . '-' . $query['NumHits']) : $link;
				$user->setSlug($link);
				$user->setPassword(
					$passwordEncoder->encodePassword(
						$user,
						$form->get('plainPassword')->getData()
					)
				);
				$user->setRoles(['ROLE_DOCTOR']);
				$address_line_1 = $form->get('address_line_1')->getData();
				$address_line_2 = $form->get('address_line_2')->getData();
				$city = $form->get('city')->getData();
				$country = $form->get('country')->getData();
				$postal_code = $form->get('postal_code')->getData();
				if(!empty($address_line_1) || !empty($address_line_2) || !empty($city)  || !empty($country) || !empty($postal_code)){
					$adresse = $address_line_2 . ' ' . $city . ' ' . $city . ' ' . $country . ' ' . $postal_code;
					$client = HttpClient::create();
					$response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $adresse) . '&destination=' . str_replace(' ', '+', $adresse) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
					$content = $response->getContent();
					$localisation = json_decode($content, true);
					$lat = isset($localisation['routes'][0]['bounds']['northeast']['lat']) ? $localisation['routes'][0]['bounds']['northeast']['lat'] : 48.8504195;
					$lng=isset($localisation['routes'][0]['bounds']['northeast']['lng']) ? $localisation['routes'][0]['bounds']['northeast']['lng'] : 2.2899323;
					$user->setLatitude($lat);
					$user->setLongitude($lng);
				}
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($user);
				$entityManager->flush();
			
				$full_name = $user->getFirstName() . ' ' .  $user->getLastName();
				$email = $user->getEmail();
			
				/*$message = (new \Swift_Message('Welcome to the DocBooking!'))
							->setFrom('docbooking0@gmail.com')
							->setTo($user->getEmail())
							->setBody(
								$this->renderView(
									'emails/registration_doctor.html.twig',
									['name' => $full_name, 'email' => $email, 'site_name' => 'DocBooking']
								),
								'text/html'
							);
				$mailer->send($message);*/
				
				$subject = 'Welcome to DocBooking!';
				$message = $this->renderView(
					'emails/registration_doctor.html.twig',
					[
						'title' 	 	=> $user->getTitle(), 
						'first_name' 	=> $user->getFirstName(), 
						'last_name' 	=> $user->getLastName(), 
						'email' 		=> $user->getEmail(), 
						'slug' 	 	=> $user->getSlug(), 
						'site_name' 	=> 'DocBooking'
					]
				);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: contact-for-doctors@docbooking.ch' . "\r\n" . 'Reply-To: contact-for-doctors@docbooking.ch' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
				@mail($email, $subject, $message, $headers);

				// do anything else you need here, like send an email

				return $guardHandler->authenticateUserAndHandleSuccess(
					$user,
					$request,
					$authenticator,
					'doctor' // firewall name in security.yaml
				);
			}
			catch (UniqueConstraintViolationException $e) {
				$this->addFlash('doctor_registration_error', 'Duplicate Account with email ' . $user->getEmail() . '!');
			}
        }
		return $this->render('registration/doctor/register.html.twig', [
			'registrationForm' => $form->createView(),
			'classBody' 		=> $class,
			'countryCode'		=> $json_data->countryCode
		]);
    }
}
