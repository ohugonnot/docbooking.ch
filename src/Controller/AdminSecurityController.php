<?php

namespace App\Controller;

use App\Entity\Admin;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $admin->setRoles(['ROLE_ADMIN','ROLE_SUPER_ADMIN']);
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
    public function dashboard()
    {
        return $this->render('security/admin/page.html.twig',[]);
    }
}
