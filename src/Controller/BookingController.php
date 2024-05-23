<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="app_booking")
     */
    public function index()
    {
        $class = 'account-page';
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
            'classBody' => $class,
        ]);
    }
}
