<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorChangePasswordFormType;
use App\Form\DoctorResetPasswordRequestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/doctor/reset-password")
 */
class DoctorResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
	private $dlproMailer;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, \Swift_Mailer $mailer2)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
		$this->dlproMailer = $mailer2;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("", name="app_doctor_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(DoctorResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }
		
		$class='account-page';

        return $this->render('reset_password/doctor/request.html.twig', [
            'requestForm' => $form->createView(),
			'classBody' => $class
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/doctor/check-email", name="app_doctor_check_email")
     */
    public function checkEmail(): Response
    {
        // We prevent users from directly accessing this page
        if (!$this->canCheckEmail()) {
            return $this->redirectToRoute('app_doctor_forgot_password_request');
        }
		
		$class='account-page';

        return $this->render('reset_password/doctor/check_email.html.twig', [
            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
			'classBody' => $class
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/doctor/reset/{token}", name="app_doctor_reset_password")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_doctor_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_doctor_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(DoctorChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('app_doctor_login');
        }
		
		$class='account-page';

        return $this->render('reset_password/doctor/reset.html.twig', [
            'resetForm' => $form->createView(),
			'classBody' => $class
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(Doctor::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Marks that you are allowed to see the app_check_email page.
        $this->setCanCheckEmailInSession();

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_doctor_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem handling your password reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_doctor_forgot_password_request');
        }

        /*$email = (new TemplatedEmail())
            ->from(new Address('ziedaifa1@gmail.com', 'DocBooking'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/doctor/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
            ])
        ;

        $mailer->send($email);*/
		
		$message = (new \Swift_Message('Your password reset request'))
						->setFrom('docbooking0@gmail.com')
						->setTo($user->getEmail())
						->setBody(
								$this->renderView(
									'reset_password/doctor/email.html.twig',
									['resetToken' => $resetToken,'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime()]
								),
								'text/html'
						);
		$this->dlproMailer->send($message);

        return $this->redirectToRoute('app_doctor_check_email');
    }
}
