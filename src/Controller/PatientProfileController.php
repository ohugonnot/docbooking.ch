<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientProfileChangePasswordType;
use App\Form\PatientProfileSettingsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PatientProfileController extends AbstractController
{
	private $passwordEncoder;
	
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/patient/profile", name="patient_profile")
     */
    public function index()
    {
        return $this->render('profile/patient/index.html.twig', [
            'controller_name' => 'PatientProfileController',
        ]);
    }
	
	/**
     * @Route("/patient/dashboard", name="app_patient_dashboard")
     */
    public function dashboard()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_patient_login');
		}
		
		$patient = $this->getDoctrine()->getRepository(Patient::class)->find($this->getUser()->getId());
		
		$appointments = $patient->getAppointments();

		$class='account-page';
        return $this->render('profile/patient/index.html.twig', [
            'controller_name' => 'PatientProfileController',
			'classBody'       => $class,
			'appointments'     => $appointments
        ]);
    }

	/**
     * @Route("/patient/profile_settings", name="app_patient_profile_settings")
     */
    public function profile_settings(Request $request, SluggerInterface $slugger)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_patient_login');
		}

		$user = $this->getUser();

		$form = $this->createForm(PatientProfileSettingsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			/** @var UploadedFile $picture_profile */
			$picture_profile = $form->get('picture_profile')->getData();
			
			// this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($picture_profile) {
                $originalFilename = pathinfo($picture_profile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture_profile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $picture_profile->move(
                        $this->getParameter('patient_directory'),
                        $newFilename
                    );
					$user->setPictureProfile($request->getBaseURL() . '/uploads/patients/' . $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
					$this->addFlash('patient_profile_social_error', 'Your Picture Profile has not been changed!');
                }
			}

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();
			$this->addFlash('patient_profile_social_success', 'Your Profile has been changed!');

        }

		$class='account-page';

        return $this->render('profile/patient/profile_settings.html.twig', [
            'controller_name' => 'PatientProfileController',
			'requestForm' => $form->createView(),
			'classBody'       => $class,
			'currentUser'=>$user
        ]);
    }

	/**
     * @Route("/patient/change_password", name="app_patient_profile_change_password")
     */
    public function change_password(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$user = $this->getUser();
		
		$form = $this->createForm(PatientProfileChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			if($this->passwordEncoder->isPasswordValid($user, $form->get('oldPassword')->getData())) {
				
				// encode the plain password
				$user->setPassword(
					$this->passwordEncoder->encodePassword(
						$user,
						$form->get('plainPassword')->getData()
					)
				);
				
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($user);
				$entityManager->flush();

				$this->addFlash('patient_profile_change_password_success', 'Your Password has been changed!');
			} else {
				$this->addFlash('patient_profile_change_password_error', 'The old password you have entered is incorrect!');
			}
        }
		
		$class='account-page';

        return $this->render('profile/patient/change_password.html.twig', [
            'controller_name' => 'PatientProfileController',
			'requestForm' => $form->createView(),
			'classBody'       => $class,
			'currentUser'=>$user
        ]);
    }

	/**
     * @Route("/patient/favourites", name="app_patient_profile_favourites")
     */
    public function favourites()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_patient_login');
		}
		$class='account-page';
        return $this->render('profile/patient/favourites.html.twig', [
            'controller_name' => 'PatientProfileController',
			'classBody'       => $class
        ]);
    }
}
