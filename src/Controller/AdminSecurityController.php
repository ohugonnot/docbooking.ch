<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function doctors(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Doctor::class)->createQueryBuilder('d');
        $search = $request->query->get('search',null);
        if($search)
            $query->andWhere('d.last_name LIKE :search')
                ->orWhere('d.first_name LIKE :search')
                ->orWhere('d.email LIKE :search')
                ->orWhere('d.address_line_1 LIKE :search')
                ->orWhere('d.city LIKE :search')
                ->orWhere('d.phone_number LIKE :search')
                ->orWhere('d.country LIKE :search')
                ->orWhere('d.postal_code LIKE :search')
                ->orWhere('d.speciality LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        $limit = $request->query->getInt('limit',25);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit,
            [
                'defaultSortFieldName'      => 'd.create_at',
                'defaultSortDirection' => 'DESC'
            ]
        );
        $doctors = $em->getRepository(Doctor::class)->findBy([], ['first_name' => 'ASC']);
        return $this->render('admin/doctors.html.twig', ['doctors' => $doctors, 'pagination'=>$pagination, 'limit'=>$limit, 'search'=>$search]);
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
    public function patients(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Patient::class)
            ->createQueryBuilder('p');

        $search = $request->query->get('search',null);
        if($search)
            $query->andWhere('p.last_name LIKE :search')
                ->orWhere('p.first_name LIKE :search')
                ->orWhere('p.email LIKE :search')
                ->orWhere('p.address LIKE :search')
                ->orWhere('p.city LIKE :search')
                ->orWhere('p.phone_number LIKE :search')
                ->orWhere('p.country LIKE :search')
                ->orWhere('p.postal_code LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        $limit = $request->query->getInt('limit',25);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit,
            [
                'defaultSortFieldName'      => 'p.create_at',
                'defaultSortDirection' => 'DESC'
            ]
        );

        $patients = $em->getRepository(Patient::class)->findBy([], ['first_name' => 'ASC']);
        return $this->render('admin/patients.html.twig', ['patients' => $patients, 'pagination'=>$pagination, 'limit'=>$limit, 'search' => $search]);
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
    public function appointments(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Appointment::class)->createQueryBuilder('a')
            ->leftJoin('a.doctor', 'd')
            ->leftJoin('a.patient', 'p')
            ->addSelect('d')
            ->addSelect('p');
        $search = $request->query->get('search',null);
        if($search)
            $query->andWhere('d.last_name LIKE :search')
                ->orWhere('d.first_name LIKE :search')
                ->orWhere('p.last_name LIKE :search')
                ->orWhere('p.first_name LIKE :search')
                ->orWhere('a.app_date LIKE :search')
                ->orWhere('a.app_time LIKE :search')
                ->orWhere('a.product_price LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        $limit = $request->query->getInt('limit',25);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit,
            [
                'defaultSortFieldName'      => 'a.app_time',
                'defaultSortDirection' => 'DESC'
            ]
        );
        $appointments = $em->getRepository(Appointment::class)->findBy([], ['create_time' => 'DESC']);
        return $this->render('admin/appointments.html.twig', ['appointments' => $appointments, 'pagination'=>$pagination, 'limit'=>$limit, 'search'=>$search]);
    }

    /**
     * @Route("admin/appointment/delete/{id}", name="app_appointment_delete")
     */
    public function deleteAppointment(Appointment $appointment): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($appointment);
        $em->flush();
        return $this->redirectToRoute('app_admin_appointments');
    }

    /**
     * @Route("clean", name="app_admin_clean")
     */
    public function clean(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $doctors = $em->getRepository(Doctor::class)->findAll();
        foreach($doctors as $doctor)
        {
            if($doctor->getLangOther() === 'Choose a Language...')
            {
                $doctor->setLangOther(null);
                $em->flush();
            }


        }
        die();
        return new Response('');
    }

}
