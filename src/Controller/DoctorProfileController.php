<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\DoctorSocial;
use App\Entity\Timing;
use App\Entity\Appointment;
use App\Entity\Patient;
use App\Repository\DoctorRepository;
use App\Form\DoctorProfileChangePasswordType;
use App\Form\DoctorProfileSocialFormType;
use App\Form\DoctorProfileSettingsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\String\Slugger\SluggerInterface;

class DoctorProfileController extends AbstractController
{
	private $self = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__';
	private $approve = 'https://www.sandbox.paypal.com/checkoutnow?token=__ORDERID__';
	private $update = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__';
	private $capture = 'https://api.sandbox.paypal.com/v2/checkout/orders/__ORDERID__/capture';
	
	private $passwordEncoder;
	
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
	
	/**
     * @Route("/doctor/{slug}.html", name="app_doctor_profile")
     */
    public function profile($slug, Request $request, DoctorRepository $doctorRepository)
    {
		/** @var User $user */
		$user = $this->getUser();
		if (!$user) {
			$token_storage = $this->container->get('security.token_storage');
			/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
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
			/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
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
		$doctor = $this->getDoctrine()->getRepository(Doctor::class)->findOneBySlugField($slug);
		$class='account-page';
		$adresse = $doctor->getAddressLine1() . ' ' . $doctor->getCity() . ' ' . $doctor->getState() . ' ' . $doctor->getCountry() . ' ' . $doctor->getPostalCode();
		$client = HttpClient::create();
		$response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $adresse) . '&destination=' . str_replace(' ', '+', $adresse) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
		$content = $response->getContent();
		$localisation = null;
		$lat = 0;
		$lng = 0;
		if($content){
			$localisation = json_decode($content, true);
		}
		if(!empty($localisation['routes'][0]['bounds']['northeast']['lat']) && !empty($localisation['routes'][0]['bounds']['northeast']['lng'])){
			$lat = $localisation['routes'][0]['bounds']['northeast']['lat'];
			$lng = $localisation['routes'][0]['bounds']['northeast']['lng'];
		}
		$isDoctor = false;
		if($user){
			$roles = $user->getRoles();
			if(in_array('ROLE_DOCTOR', $roles)){
				$isDoctor = true;
			}
		}
        return $this->render('profile/doctor/profile.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'doctor'       	  => $doctor,
			'lat'			  => $lat,
			'lng'			  => $lng,
			'isDoctor'		  => $isDoctor
        ]);
    }
	
	/**
     * @Route("/doctor/dashboard", name="app_doctor_dashboard")
     */
    public function dashboard()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		$doctor = $this->getUser();
		$appointments_stat = $doctor->getAppointments();
		$appointment = $this->getDoctrine()->getRepository(Appointment::class)->findByNotTodayDate($this->getUser()->getId());
		$appointments = $this->getDoctrine()->getRepository(Appointment::class)->findByNotTodayDate($this->getUser()->getId());
		$appointments_today = $this->getDoctrine()->getRepository(Appointment::class)->findByTodayDate($this->getUser()->getId());
		$patients_is = [];
		$patients = [];
		foreach($appointments_stat as $appointment){
			$patients[] = $appointment->getPatientID()->getId();
		}
		$patientsIds = array_unique ($patients);
		$patients_today = [];
		foreach($appointments_today as $appointment){
			$patients[] = $appointment->getPatientID()->getId();
		}
		$patients_today = array_unique ($patients);
		$class='account-page';
        return $this->render('profile/doctor/index.html.twig', [
            'controller_name' 		=> 'DoctorProfileController',
			'classBody'       		=> $class,
			'appointments_count'    => count($appointments_stat),
			'patients_count'       	=> count($patientsIds),
			'patients_count_today'  => count($patients_today),
			'appointments'       	=> $appointments,
			'appointments_today'    => $appointments_today,
			'doctor'				=> $doctor
        ]);
    }
	
	/**
     * @Route("/doctor/changepassword", name="app_doctor_changepassword")
     */
    public function changepassword(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$user = $this->getUser();
		
		$form = $this->createForm(DoctorProfileChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			//$oldPassword = $request->request->get('change_password')['oldPassword'];
			//var_dump($form->get('oldPassword')->getData());die;
			
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

				$this->addFlash('doctor_profile_change_password_success', 'Your Password has been changed!');
				
				$this->addFlash('notice', 'Votre mot de passe à bien été changé !');
			} else {
				$this->addFlash('doctor_profile_change_password_error', 'The old password you have entered is incorrect!');
			}
        }
		
		$class='account-page';
		
        return $this->render('profile/doctor/change_password.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'requestForm' => $form->createView(),
			'classBody'       => $class,
			'doctor'=>$user
        ]);
    }
	/**
     * @Route("/doctor/social", name="app_doctor_social")
     */
    public function social(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}

		$user_social = $this->getUser()->getDoctorSocial();
		if(!$user_social){
			$user_social = new DoctorSocial();
		}
		
		$form = $this->createForm(DoctorProfileSocialFormType::class, $user_social);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$this->getUser()->setDoctorSocial($user_social);
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user_social);
			$entityManager->flush();
			$this->addFlash('doctor_profile_social_success', 'Your Profile Social has been changed!');
        }
		$class='account-page';
        return $this->render('profile/doctor/social.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'requestForm' => $form->createView(),
			'classBody'       => $class,
			'doctor'       => $this->getUser()
        ]);
    }
	/**
     * @Route("/doctor/profilesettings", name="app_doctor_profilesettings")
     */
    public function profilesettings(Request $request, SluggerInterface $slugger)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}

		$user = $this->getUser();

		$form = $this->createForm(DoctorProfileSettingsFormType::class, $user);
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
                        $this->getParameter('doctor_directory'),
                        $newFilename
                    );
					$user->setPictureProfile($request->getBaseURL() . '/uploads/doctors/' . $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
					$this->addFlash('doctor_profile_social_success', 'Your Picture Profile has not been changed!');
                }
			}
			
			$address_line_1 = $form->get('address_line_1')->getData();
			$address_line_2 = $form->get('address_line_2')->getData();
			$city = $form->get('city')->getData();
			$state = $form->get('state')->getData();
			$country = $form->get('country')->getData();
			$postal_code = $form->get('postal_code')->getData();
			
			if(!empty($address_line_1) || !empty($address_line_2) || !empty($city) || !empty($state)  || !empty($country) || !empty($postal_code)){
				$adresse = $address_line_1 . ' ' . $address_line_2 .  ' ' . $city . ' ' . $state . ' ' . $country . ' ' . $postal_code;
				$client = HttpClient::create();
				$response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $adresse) . '&destination=' . str_replace(' ', '+', $adresse) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
				$content = $response->getContent();
				$localisation = json_decode($content, true);
				$lat = isset($localisation['routes'][0]['bounds']['northeast']['lat']) ? $localisation['routes'][0]['bounds']['northeast']['lat'] : 48.8504195;
				$lng=isset($localisation['routes'][0]['bounds']['northeast']['lng']) ? $localisation['routes'][0]['bounds']['northeast']['lng'] : 2.2899323;
				$user->setLatitude($lat);
				$user->setLongitude($lng);
			}
			
			foreach($user->getIdClinic() as &$clinic){
				$clinic->setDoctorID($this->getUser());
			}
			foreach($user->getEducation() as &$education){
				$education->setIdDoctor($this->getUser());
			}
			foreach($user->getExperience() as &$experience){
				$experience->setIdDoctor($this->getUser());
			}
			foreach($user->getAwards() as &$awards){
				$awards->setIdDoctor($this->getUser());
			}
			foreach($user->getMemberships() as &$memberships){
				$memberships->setIdDoctor($this->getUser());
			}
			foreach($user->getRegistrations() as &$registrations){
				$registrations->setIdDoctor($this->getUser());
			}
			$link = $user->getUrlProfile();
			$slug = $user->getSlug();
			$dql   = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.slug=:link AND a.id <> :id";
			$em = $this->getDoctrine()->getManager();
			$qy = $em->createQuery($dql);
			$qy->setParameter('link', $link);
			$qy->setParameter('id', $user->getId());
			$query = $qy->getOneOrNullResult();
			$link_new = ($query['NumHits'] > 0) ? ($link . '-' . $query['NumHits']) : $link;
			if($slug !== $link){
				$user->setSlug($link_new);
			}
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();
			$this->addFlash('doctor_profile_social_success', 'Your Profile has been updated !');
        }
		$class='account-page';
        return $this->render('profile/doctor/profile_settings.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'requestForm' => $form->createView(),
			'classBody'       => $class,
			'userID'			=> $user->getID(),
			'doctor'=>$user
        ]);
    }
	
	public function formatHeaderTiming($dt, $isPrev = false){
		if(!$dt){
			$date = new \DateTime();
			$next_date = new \DateTime();
			$prev_date = new \DateTime();
		}
		else{
			$datef = date('m/d/Y h:i:s a', $dt);
			$date = new \DateTime($datef);
			$next_date = new \DateTime($datef);
			$prev_date = new \DateTime($datef);
		}
		$next_date->setISODate($next_date->format('o'), $next_date->format('W') + 1);
		$prev_date->setISODate($prev_date->format('o'), $prev_date->format('W') - 1);
		
		if(!$isPrev){
			//$date->setISODate($date->format('o'), $date->format('W') - 1);
		}
		else{
			//$date->setISODate($date->format('o'), $date->format('W') + 1);
		}
		$date->setISODate($date->format('o'), $date->format('W'));
		$periods = new \DatePeriod($date, new \DateInterval('P1D'), 6);
		$response = [];
		foreach($periods as $period){
			$response['time'][] = [
				$period->format('j'),
				$period->format('m'),
				$period->format('y')
			];
		}
		$response['next'] = $next_date;
		$response['prev'] = $prev_date;
		return $response;
	}
	
	public function convertToHoursMins($time) {
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return [$hours, $minutes];
	}
	
	/**
     * @Route("/doctor/scheduletimings/save", name="app_doctor_schedule_timings_save")
     */
    public function save(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$user = $this->getUser();
		
		$json_data = $request->request->get('data');
		$time_slot = $request->request->get('time_slot');
		$data = json_decode($json_data, true);
		
		foreach($data as $dt){
			$step = $dt['step'];
			if($dt['time']!='20:0' || $dt['time']=='20:0'){
				$data_time[] = [
					'selected'	=>	$dt['selected'],
					'time'		=> $dt['time']
				];
			}
			if($dt['time']=='20:0'){
				$final_data[] = [
					'id'	=>	$dt['id'],
					'day'	=> 	$dt['day'],
					'month'	=>	$dt['month'],
					'year'	=>  $dt['year'],
					'times'	=>  json_encode($data_time)
				];
				$data_time = [];
			}
		}

		if ($final_data) {
			foreach($final_data as $data){
				$time = null;
				if($data['id']){
					$time = $this->getDoctrine()->getRepository(Timing::class)->find($data['id']);
				}
				else{
					$time = new Timing();
				}
				$time->setDay($data['day']);
				$time->setMonth($data['month']);
				$time->setYear($data['year']);
				$time->setTimes($data['times']);
				$time->setTimeSlot($time_slot);
				$time->setIdDoctor($user);
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($time);
				$entityManager->flush();
			}
			
			$this->addFlash('doctor_profile_timing_success', 'Your Schedule Timings has been changed!');
        }
		
		
		return $this->json($request->request->get('data'));
    }
	
	 public function getStartAndEndDate($week, $year) {
		$dto = new \DateTime();
		$dto->setISODate($year, $week);
		$ret['week_start'] = [
			'day'	=>	 $dto->format('j'),
			'month'	=>	 $dto->format('n'),
			'year'	=>	 $dto->format('Y')
		];
		$dto->modify('+6 days');
		$ret['week_end'] = [
			'day'	=>	 $dto->format('j'),
			'month'	=>	 $dto->format('n'),
			'year'	=>	 $dto->format('Y')
		];
		return $ret;
	}
	
	public function searchForId($time, $array) {
		foreach ($array as $val) {
			if ($val['time'] === $time) {
				return $val['selected'];
			}
		}
		return 'false';
	}
	
	public function getNextWeek(){
		$dt = new \DateTime();
		// create DateTime object with current time
		
		$dt->setISODate($dt->format('o'), $dt->format('W') + 1);
		// set object to Monday on next week
		
		$periods = new \DatePeriod($dt, new \DateInterval('P1D'), 6);
		// get all 1day periods from Monday to +6 days
		
		$next_week = [
			0	=>	[], 
			1	=>	[], 
			2	=>	[]
		];
		$first = false;
		foreach($periods as $period){
			if(!$first){
				$next_week[0] = [
					$period->format('m')
				];
				$next_week[2] = [
					$period->format('Y')
				];
				$first = true;
			}
			$next_week[1][] = $period->format('j');
		}
		return $next_week;
	}
	
	public function saveSession(){
		
	}

	/**
     * @Route("/doctor/scheduletimings", name="app_doctor_schedule_timings")
     */
    public function scheduletimings(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		$time_stamp=null;
		if($request->query->get('next')){
			$time_stamp=$request->query->get('next');
		}
		else if($request->query->get('prev')){
			$time_stamp=$request->query->get('prev');
		}
		$is_next_prev=false;
		$url_vars = [];
		if($request->query->get('next')){
			$is_next_prev=false;
			$url_vars = [
				'name'	=>	'next',
				'value'	=>	$request->query->get('next')
			];
		}
		else if($request->query->get('prev')){
			$is_next_prev=true;
			$url_vars = [
				'name'	=>	'next',
				'value'	=>	$request->query->get('prev')
			];
		}
		$doctor = $this->getUser();
		//$date = date('m/d/Y h:i:s a', $time_stamp);
		//$date = new \DateTime($date);
		//$week = $date->format("W");
		//$weekStartEnd = $this->getStartAndEndDate($week, $date->format("Y"));
		$allTiming = $this->getUser()->getIdTiming();
		$allTimingArray = [];
		foreach($allTiming as $timing){
			$allTimingArray[$timing->getDay().$timing->getMonth().$timing->getYear()] = [
				'id'	=>	$timing->getId(),
				'day'	=>	$timing->getDay(),
				'month'	=>	$timing->getMonth(),
				'year'	=>	$timing->getYear(),
				'times'	=>	$timing->getTimes(),
				'time_slot'	=>	$timing->getTimeSlot(),
				
			];
		}
		$slotRequested=$request->query->get('slot');
		$slot = 3600;
		switch($slotRequested){
			case '15':
				$slot = 900;
				break;
			case '30':
				$slot = 1800;
				break;
			case '45':
				$slot = 2700;
				break;
			case '20':
				$slot = 1200;
				break;
			case '1':
				$slot = 3600;
				break;
			case 'all':
				$slot = 60;
				break;
			default:
				$slot = 3600;
		}
		$timing = $this->formatHeaderTiming($time_stamp, $is_next_prev);
		$output = [];
		$output[] = '<!-- Schedule Header -->' . "\n";
		$output[] = '<div class="schedule-header">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";
		$output[] = '<!-- Day Slot -->' . "\n";
		$output[] = '<div class="day-slot dlpro-calendar-render">' . "\n";
		$output[] = '<!-- Day Slot -->' . "\n";
		$output[] = '<div class="day-slot">' . "\n";
		$output[] = '<ul>' . "\n";
		$output[] = '<li class="left-arrow">' . "\n";
		$output[] = '<a id="dlproPrev" href="?prev='.$timing['prev']->getTimestamp().'">' . "\n";
		$output[] = '<i class="fa fa-chevron-left"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		$timingDB = $doctor->getIdTiming();
		foreach($timing['time'] as $time){
			$date= $time[0] . '-' .  $time[1] . '-' .  $time[2];
			$dateObj   = \DateTime::createFromFormat('j-m-Y', $date);
			$monthName = $dateObj->format('F');
			$dayName = $dateObj->format('D');
			$output[] = '<li>' . "\n";
			$output[] = '<span>' . $dayName . '</span>' . "\n";
			$output[] = '<span class="slot-date">' . $time[0] . ' ' . $monthName . ' <small class="slot-year">' . $time[2] . '</small></span>' . "\n";
			$output[] = '</li>' . "\n";
		}
		$output[] = '<li class="right-arrow">' . "\n";
		$output[] = '<a id="dlproNext"  href="?next='.$timing['next']->getTimestamp().'">' . "\n";
		$output[] = '<i class="fa fa-chevron-right"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		$output[] = '</ul>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Day Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Day Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Header -->' . "\n";	
		$output[] = '<!-- Schedule Content -->' . "\n";
		$output[] = '<div class="schedule-cont">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";
		$output[] = '<!-- Time Slot -->' . "\n";
		$output[] = '<div class="time-slot dlpro-calendar-time-render">' . "\n";
		$output[] = '<ul class="clearfix">' . "\n";
		foreach($timing['time'] as $time){

			$timingDB = $this->getDoctrine()->getRepository(Timing::class)->findByDateField(
				$time[0], 
				$time[1], 
				$time[2],
				$this->getUser()
			);
			if($timingDB){
				$step = 0;
				foreach($timingDB as $tm){
					$day = $tm->getDay();
					$year = $tm->getYear();
					$month = $tm->getMonth();
					$times = json_decode($tm->getTimes(), true);
					$first = true;
					$output[] = '<li>' . "\n";
					$date_30 = [];
					for($c=25200;$c<=72000;$c=$c+1800){
						$date_30[] = $c;
					}
					$date_45 = [];
					for($k=25200;$k<72000;$k=$k+2700){
						$date_45[] = $k;
					}
					$date_20 = [];
					for($k=25200;$k<72000;$k=$k+1200){
						$date_20[] = $k;
					}
					for($j=25200;$j<=72000;$j=$j+900){
						$h = floor($j / 3600);
						$m = floor($j % 3600 / 60);
						$display = '';
						if($slot==3600 && $m!=0){
							$display = 'style="display:none"';
						}
						if($slot==1800 && !in_array($j, $date_30)){
							$display = 'style="display:none"';
						}
						if($slot==2700 && !in_array($j, $date_45)){
							$display = 'style="display:none"';
						}
						if($slot==1200 && !in_array($j, $date_20)){
							$display = 'style="display:none"';
						}
						$selected = '';
						if($this->searchForId($h.':'.$m, $times) == 'true'){
							$selected= 'selected';
						}
						$output[] = '<a class="timing dlproTimeElement ' . $selected . '" href="#" data-step="' . $step . '" data-time="' . $h.':'.$m . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '" ' . $display . '>' . "\n";
						$output[] = '<span>' . $h.':'. str_pad($m,2,'0') . '</span>' . "\n";
						$output[] = '</a>' . "\n";
						$step++;
					}
					
					$output[] = '</li>' . "\n";
				}
			}
			else{
				$step = 0;
				$output[] = '<li>' . "\n";
				for($j=25200;$j<=72000;$j=$j+$slot){
					$h = floor($j / 3600);
					$m = floor($j % 3600 / 60);
					$output[] = '<a class="timing dlproTimeElement" href="#" data-step="' . $step . '" data-time="' . $h . ':' . $m . '" data-id="" data-year="' . $time[2] . '" data-month="' . $time[1] . '" data-day="' . $time[0] . '">' . "\n";
					$output[] = '<span>' . $h . ':' . str_pad($m,2,'0') . '</span>' . "\n";
					$output[] = '</a>' . "\n";
					$step++;
				}
				$output[] = '</li>' . "\n";
			}
		}
		$output[] = '</ul>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Time Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Content -->' . "\n";
		
		
		$class='account-page';
        return $this->render('profile/doctor/schedule_timings.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'listTiming'	  => $allTimingArray,
			//'weekStartEnd'  => $weekStartEnd,
			'doctor'		  => $doctor,
			'scheduletimings' => join("\n", $output),
			'url_vars'		  => $url_vars
        ]);
    }
	
	/**
     * @Route("/doctor/appointments", name="app_doctor_appointments")
     */
    public function appointments()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($this->getUser()->getId());
		
		$appointments = $doctor->getAppointments();
		
		$class='account-page';
        return $this->render('profile/doctor/appointments.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'appointments'     => $appointments,
			'appointments_length'     => count($appointments),
			'doctor'=>$doctor
        ]);
    }
	
	/**
     * @Route("/doctor/appointments/view/{id}", name="app_doctor_appointments_view")
     */
    public function appointments_view(Appointment $appointment)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$class='account-page';
		
        return $this->render('profile/doctor/appointments_view.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'appointments'    => $appointment
        ]);
    }

	/**
     * @Route("/doctor/appointments/success/{id}", name="app_doctor_appointments_success")
     */
    public function appointments_success(Appointment $appointments)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$appointments->setStatus(1);
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($appointments);
		$entityManager->flush();
		$this->addFlash('doctor_profile_appointments_success', 'Appointment ID#' . $appointments->getId() . ' has been accepted!');
		
		return $this->redirectToRoute('app_doctor_appointments');
		
    }
	
	/**
     * @Route("/doctor/appointments/cancel/{id}", name="app_doctor_appointments_cancel")
     */
    public function appointments_cancel(Appointment $appointments)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$appointments->setStatus(3);
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($appointments);
		$entityManager->flush();
		$this->addFlash('doctor_profile_appointments_error', 'Appointment ID#' . $appointments->getId() . ' has been canceled!');
		
		return $this->redirectToRoute('app_doctor_appointments');
		
    }

	/**
     * @Route("/doctor/patients", name="app_doctor_patients")
     */
    public function patients()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		$doctor = $this->getUser();
		$appointments = $this->getUser()->getAppointments();
		$patients_is = [];
		$patients = [];
		foreach($appointments as $appointment){
			$patients[] = $appointment->getPatientID()->getId();
		}
		$patientsIds = array_unique ($patients);
		$patients = [];
		foreach($patientsIds as $id){
			$pat = $this->getDoctrine()->getRepository(Patient::class)->find($id);
			$patients[] = $pat;
		}
		$class='account-page';
        return $this->render('profile/doctor/patients.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'patients'    => $patients,
			'doctor'=>$doctor
        ]);
    }
	
	/**
     * @Route("/doctor/business_hours", name="app_doctor_business_hours")
     */
    public function business_hours()
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$business_hours = $this->getUser()->getBusinessHours();
		if($business_hours){
			$business_hours = json_decode($business_hours, true);
			$business_hours = $business_hours['business-hours'];
		}
		$class='account-page';
        return $this->render('profile/doctor/business_hours.html.twig', [
            'controller_name' => 'DoctorProfileController',
			'classBody'       => $class,
			'business_hours'    => $business_hours,
			'doctor'=>$this->getUser()
        ]);
    }
	/**
     * @Route("/doctor/business_hours/save", name="app_doctor_business_hours_save")
     */
    public function business_hours_save(Request $request)
    {
		if (!$this->getUser()) {
			return $this->redirectToRoute('app_doctor_login');
		}
		
		$doctor = $this->getDoctrine()->getRepository(Doctor::class)->find($this->getUser()->getId());
		
		$all_data = $request->request->all();
		
		if($all_data){
			$doctor->setBusinessHours(json_encode($all_data));
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($doctor);
			$entityManager->flush();
			$this->addFlash('doctor_profile_business_hours_success', 'Your Business Hours has been changed!');
		}
		
		return $this->redirectToRoute('app_doctor_business_hours');
    }
}
