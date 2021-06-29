<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientRegistrationFormType;
use App\Security\PatientAuthenticator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class PatientRegistrationController extends AbstractController
{
    /**
     * @Route("/patient/register", name="app_patient_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, PatientAuthenticator $authenticator): Response
    {
        $redirect_to = $request->query->get('redirect_to');
        if ($this->getUser()) {
            if ($redirect_to) {
                return $this->redirect($redirect_to);
            } else {
                return $this->redirectToRoute('app_patient_dashboard');
            }
        }
        $user = new Patient();
        $form = $this->createForm(PatientRegistrationFormType::class, $user);
        $form->handleRequest($request);

        $class = 'account-page';
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setRoles(['ROLE_PATIENT']);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $full_name = $user->getFirstName() . ' ' . $user->getLastName();
                $email = $user->getEmail();

                $subject = 'Welcome to the DocBooking!';
                $message = $this->renderView(
                    'emails/registration_patient.html.twig',
                    ['name' => $full_name, 'email' => $email, 'site_name' => 'DocBooking']
                );
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: contact@docbooking.ch' . "\r\n" . 'Reply-To: contact@docbooking.ch' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
                @mail($email, $subject, $message, $headers);

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'patient' // firewall name in security.yaml
                );
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('patient_registration_error', 'Duplicate Account with email ' . $user->getEmail() . '!');
            }
        }

        return $this->render('registration/patient/register.html.twig', [
            'registrationForm' => $form->createView(),
            'classBody' => $class,
            'redirect_to' => $redirect_to
        ]);
    }
}
