<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminSecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $class = 'account-page';

        return $this->render('security/admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'classBody' => $class]);
    }

    /**
     * @Route("/create_admin", name="create_admin")
     */
    public function createAdmin(UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $admin = new Admin();
        $encodedPassword = $passwordEncoder->encodePassword(
            $admin,
            'admin'
        );
        $admin->setPassword($encodedPassword);
        $admin->setEmail('admin@admin.admin');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->flush();
    }

    /**
     * @Route("/admin/logout", name="app_admin_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/admin/dashboard", name="app_admin_dashboard")
     */
    public function dashboard(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $nb_doctors = $em->getRepository(Doctor::class)->count([]);
        $nb_patients = $em->getRepository(Patient::class)->count([]);
        $nb_appointments = $em->getRepository(Appointment::class)->count([]);
        $last_patients = $em->getRepository(Patient::class)->findBy([], ['create_at' => 'DESC'], 5);
        $last_doctors = $em->getRepository(Doctor::class)->findByLastAppointement();
        $last_appointments = $em->getRepository(Appointment::class)->findBy([], ['create_time' => 'DESC'], 5);
        return $this->render('/admin/page.html.twig', [
            'nb_doctors' => $nb_doctors,
            'nb_patients' => $nb_patients,
            'nb_appointments' => $nb_appointments,
            'last_patients' => $last_patients,
            'last_doctors' => $last_doctors,
            'last_appointments' => $last_appointments,
        ]);
    }

    /**
     * @Route("/admin/doctors", name="app_admin_doctors")
     */
    public function doctors(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $doctors = $em->getRepository(Doctor::class)->findBy([], ['first_name' => 'ASC']);
        return $this->render('admin/doctors.html.twig', ['doctors' => $doctors]);
    }

    /**
     * @Route("admin/doctor/delete/{id}", name="app_doctor_delete")
     */
    public function deleteDoctor(Doctor $doctor): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($doctor);
        $em->flush();
        return $this->redirectToRoute('app_admin_doctors');
    }

    /**
     * @Route("/admin/patients", name="app_admin_patients")
     */
    public function patients(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $patients = $em->getRepository(Patient::class)->findBy([], ['first_name' => 'ASC']);
        return $this->render('admin/patients.html.twig', ['patients' => $patients]);
    }

    /**
     * @Route("admin/patient/delete/{id}", name="app_patient_delete")
     */
    public function deletePatient(Patient $patient): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();
        return $this->redirectToRoute('app_admin_patients');
    }

    /**
     * @Route("/admin/appointments", name="app_admin_appointments")
     */
    public function appointments(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $appointments = $em->getRepository(Appointment::class)->findBy([], ['create_time' => 'DESC']);
        return $this->render('admin/appointments.html.twig', ['appointments' => $appointments]);
    }
}
