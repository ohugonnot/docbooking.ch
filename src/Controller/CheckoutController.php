<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Timing;
use App\Service\PayPalService;
use App\Service\PayPalV2Service;
use DateTime;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    private $self = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__';
    private $approve = 'https://www.sandbox.paypal.com/checkoutnow?token=__ORDERID__';
    private $update = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__';
    private $capture = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__/capture';

    /**
     * @Route("/checkout/redirect", name="app_checkout_redirect")
     */
    public function checkout_redirect(Request $request, UrlHelper $urlHelper, Session $session)
    {
        $doctorID = $request->request->get('doctorid');
        $timeID = $request->request->get('timeid');
        $time = $request->request->get('time');

        if (!$doctorID) {
            return $this->redirectToRoute('app_index');
        }
        if (!$timeID) {
            return $this->redirectToRoute('app_index');
        }
        if (!$time) {
            return $this->redirectToRoute('app_index');
        }

        $session->set('data', [
            'doctorid' => $doctorID,
            'timeid' => $timeID,
            'time' => $time
        ]);

        $user = $this->getUser();
        if (!$user) {
            $redirect_to = $urlHelper->getAbsoluteUrl($this->generateUrl('app_checkout'));
            return $this->redirectToRoute('app_patient_register', [
                'redirect_to' => $redirect_to
            ]);
        }

        return $this->redirectToRoute('app_checkout');
    }

    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function index(Request $request, UrlHelper $urlHelper, Session $session)
    {
        $data = $session->get('data');

        $doctorID = $data['doctorid'];
        $timeID = $data['timeid'];
        $time = $data['time'];

        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($doctorID);
        if (!$doctor) {
            return $this->redirectToRoute('app_index');
        }
        $timing = $this->getDoctrine()->getRepository(Timing::class)->find($timeID);
        if (!$timing) {
            return $this->redirectToRoute('app_index');
        }
        $user = $this->getUser();
        if (!$user) {
            $token_storage = $this->container->get('security.token_storage');
            /** @var Session $session */
            $session = $GLOBALS['request']->getSession();
            $security_token = $session->get('_security_patient');
            $token = unserialize($security_token);
            if ($token) {
                $token_storage->setToken($token);
                $user = $this->getUser();
                $user_repository = $this->getDoctrine()->getRepository(Patient::class);
                $user = $user_repository->find($user->getId());
            }
        }
        /*if (!$user) {
            $redirect_to = $urlHelper->getAbsoluteUrl($this->generateUrl('app_checkout', [
                'doctor'	=>	$doctor->getId(),
                'id' 		=> 	$timing->getId(),
                'time'		=> 	$time
            ]));
            return $this->redirectToRoute('app_patient_register', [
                'redirect_to'	=>	$redirect_to
            ]);
        }*/
        $price = $doctor->getPriceCustomValue();
        $y = date('Y', strtotime($timing->getYear() . '-' . $timing->getMonth() . '-' . $timing->getDay()));
        $date_chekout = $timing->getDay() . ' ' . $timing->getNameOfMonth() . ' ' . $y;
        $class = 'account-page';

        $fee = 0;
        $total = $price + $fee;
        $subtotal = $total - $fee;

        $this->get('session')->set('timing', $timing->getId());
        $this->get('session')->set('price', $price);
        $this->get('session')->set('name', 'Appointment: Dr. ' . $doctor->getFirstName() . ' ' . $doctor->getLastName());
        $this->get('session')->set('sku', 'appointment-dr-' . strtolower($doctor->getFirstName() . '-' . $doctor->getLastName()) . '-' . uniqid());
        $this->get('session')->set('tax', $fee);
        $this->get('session')->set('subtotal', $subtotal);
        $this->get('session')->set('total', $total);
        $this->get('session')->set('doctor', $doctor);
        $this->get('session')->set('app_date', $timing->getYear() . '-' . $timing->getMonth() . '-' . $timing->getDay());
        $this->get('session')->set('app_time', $time);

        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'classBody' => $class,
            'date_chekout' => $date_chekout,
            'user' => $user,
            'timeSelected' => $time,
            'daySelected' => $timing->getDay(),
            'monthSelected' => $timing->getMonth(),
            'yearSelected' => $timing->getYear(),
            'price' => $price,
            'doctor' => $doctor
        ]);
    }

    /**
     * @Route("/checkout/payment", name="app_checkout_payment")
     */
    public function payment(PayPalV2Service $payPalService, UrlHelper $urlHelper, Request $request)
    {
        $insuranceType = $request->request->get('insuranceType');
        $insuranceNum = $request->request->get('insuranceNum');
        $terms_accept = $request->request->get('terms_accept');
        $terms_payment_method = $request->request->get('payment_method');

        if (!$terms_accept) {
            $this->addFlash('patient_checkout_error', 'You must accept Terms & Conditions!');
            return $this->redirectToRoute('app_checkout', [
                'doctor' => $this->getUser()->getId(),
                'id' => $this->get('session')->get('timing'),
                'time' => $this->get('session')->get('app_time'),
            ]);
        }

        $date = $this->get('session')->get('app_date');
        $time = $this->get('session')->get('app_time');

        $appointments = $this->getDoctrine()->getRepository(Appointment::class)->findExistAppointment($date, $time);

        if (count($appointments)) {
            $this->addFlash('patient_checkout_error', 'Appointment already Taken!');
            return $this->redirectToRoute('app_checkout', [
                'doctor' => $this->getUser()->getId(),
                'id' => $this->get('session')->get('timing'),
                'time' => $this->get('session')->get('app_time'),
            ]);
        }

        $order = [
            'order' => [
                'name' => $this->get('session')->get('name'),
                'price' => $this->get('session')->get('price'),
                'sku' => $this->get('session')->get('sku'),
                'tax' => $this->get('session')->get('tax'),
                'subtotal' => $this->get('session')->get('subtotal'),
                'total' => $this->get('session')->get('total'),
                'success_url' => $urlHelper->getAbsoluteUrl($this->generateUrl('app_checkout_payment_success')),
                'failure_url' => $urlHelper->getAbsoluteUrl($this->generateUrl('app_checkout_payment_failure'))
            ]
        ];

        $pay = false;
        $status = Appointment::$STATUT_PENDIND;
        $is_paid = Appointment::$STATUT_NOT_PAIED;
        $order_id = 0;
        $create_time = date("c");
        $date_paied = false;
        if ($terms_payment_method == 'paypal') {
            $responnse = $payPalService->createOrder($order);
            if ($responnse->result) {
                $pay = true;
                $order_id = $responnse->result->id;
                $create_time = $responnse->result->create_time;
            }
        } else if ($terms_payment_method == 'confirm_booking') {
            $pay = true;
            $status = Appointment::$STATUT_COMPLETE;
            $is_paid = Appointment::$STATUT_PAIED;
            $date_paied = date('c');
        }

        if ($pay) {
            $patient = $this->getDoctrine()->getRepository(Patient::class)->find($this->getUser()->getId());
            $doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($this->get('session')->get('doctor')->getId());
            $appointment = new Appointment();
            $appointment->setProductName($this->get('session')->get('name'));
            $appointment->setProductSku($this->get('session')->get('sku'));
            $appointment->setProductPrice($this->get('session')->get('price'));
            $appointment->setProductSubtotal($this->get('session')->get('subtotal'));
            $appointment->setProductTotal($this->get('session')->get('total'));
            $appointment->setStatus($status);
            $appointment->setPatient($patient);
            $appointment->setDoctor($doctor);
            $appointment->setIsPayed($is_paid);
            $appointment->setOrderID($order_id);
            $appointment->setCreateTime(DateTime::createFromFormat(DateTimeInterface::ISO8601, $create_time));
            if ($date_paied) {
                $appointment->setDatePaied(DateTime::createFromFormat(DateTimeInterface::ISO8601, $date_paied));
            }
            $date = $this->get('session')->get('app_date');
            $time = $this->get('session')->get('app_time');
            $ds = date('y-n-j H:i', strtotime($date . ' ' . $time));
            $appointment->setAppTime(DateTime::createFromFormat('y-n-j H:i', $ds));
            $appointment->setAppDate(DateTime::createFromFormat('y-n-j H:i', $ds));
            //$appointment->setInsuranceType($insuranceType);
            //$appointment->setInsuranceNum($insuranceNum);
            $doctor = $this->get('session')->get('doctor');
            $patient = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();
            $subject_doctor = 'Confirmation DocBooking ID#' . $appointment->getId() . ' for patient ' . $patient->getFirstName() . ' ' . $patient->getLastName();
            $subject_patient = 'Confirmation DocBooking ID#' . $appointment->getId() . ' for patient ' . $patient->getFirstName() . ' ' . $patient->getLastName();
            $to_doctor = $doctor->getEmail();
            $to_patient = $patient->getEmail();
            $this->sendMail($to_doctor, $subject_doctor, $doctor, $patient, $appointment, 'emails/booking_doctor.html.twig');
            $this->sendMail($to_patient, $subject_patient, $doctor, $patient, $appointment, 'emails/booking_patient.html.twig');
        }

        return $this->redirectToRoute('app_patient_dashboard');
    }

    public function sendMail($to, $subject, $doctor, $patient, $appointment, $template)
    {
        $message = $this->renderView(
            $template,
            [
                'doctor' => $doctor,
                'patient' => $patient,
                'appointment' => $appointment,
                'site_name' => 'DocBooking'
            ]
        );
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: contact@docbooking.ch' . "\r\n" . 'Reply-To: contact@docbooking.ch' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        @mail($to, $subject, $message, $headers);
    }

    /**
     * @Route("/checkout/payment/success", name="app_checkout_payment_success")
     */
    public function success(PayPalService $payPalService)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            $token_storage = $this->container->get('security.token_storage');
            /** @var Session $session */
            $session = $GLOBALS['request']->getSession();
            $security_token = $session->get('_security_patient');
            $token = unserialize($security_token);
            if ($token) {
                $token_storage->setToken($token);
                $user = $this->getUser();
                $user_repository = $this->getDoctrine()->getRepository(Patient::class);
                $user = $user_repository->find($user->getId());
            }
        }
        if (!$user) {
            $token_storage = $this->container->get('security.token_storage');
            /** @var Session $session */
            $session = $GLOBALS['request']->getSession();
            $security_token = $session->get('_security_doctor');
            $token = unserialize($security_token);
            if ($token) {
                $token_storage->setToken($token);
                $user = $this->getUser();
                $user_repository = $this->getDoctrine()->getRepository(Doctor::class);
                $user = $user_repository->find($user->getId());
            }
        }
        $date = new DateTime();
        $order = $_REQUEST;
        $appointment = $this->getDoctrine()->getRepository(Appointment::class)->findOneByOrderId($_REQUEST['token']);
        $appointment->setIsPayed(Appointment::$STATUT_PAIED);
        $appointment->setDatePaied($date);
        $appointment->setStatus(Appointment::$STATUT_COMPLETE);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($appointment);
        $entityManager->flush();
        $class = 'account-page';
        return $this->render('checkout/success.html.twig', [
            'controller_name' => 'CheckoutController',
            'classBody' => $class,
            'appointment' => $appointment,
            'doctor' => $appointment->getDoctor()
        ]);
    }

    /**
     * @Route("/checkout/payment/failure", name="app_checkout_payment_failure")
     */
    public function failure(PayPalService $payPalService)
    {
        $order = $_REQUEST;
        $appointment = $this->getDoctrine()->getRepository(Appointment::class)->findOneByOrderId($_REQUEST['token']);
        $appointment->setIsPayed(Appointment::$STATUT_NOT_PAIED);
        $appointment->setStatus(Appointment::$STATUT_CANCELLED);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($appointment);
        $entityManager->flush();
        return $this->redirectToRoute('app_patient_dashboard');
    }
}
