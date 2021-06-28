<?php
namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Doctor;
use App\Entity\Appointment;
use App\Repository\DoctorRepository;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
	private $cityArray = [];
	/**
	 * @Route("/privacy-policy", name="app_privacy_policy")
	 */
	public function privacyPolicy()
	{
		$class = '';
		return $this->render('pages/privacy_policy.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}
	/**
	 * @Route("/term-condition", name="app_term_condition")
	 */
	public function termCondition()
	{
		$class = '';
		return $this->render('pages/term_condition.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}
	/**
	 * @Route("/about", name="app_about")
	 */
	public function about()
	{
		$class = '';
		return $this->render('pages/about.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}

	/**
	 * @Route("/help-for-doctors", name="app_help_for_doctors")
	 */
	public function helpForDoctor()
	{
		$class = '';
		return $this->render('pages/help_for_doctors.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}

	/**
	 * @Route("/404", name="app_404")
	 */
	public function wrong404()
	{
		$class = '';
		return $this->render('pages/404.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}

	/**
	 * @Route("/ddx", name="app_ddx")
	 */
	public function ddx()
	{
		$class = '';
		return $this->render('pages/ddx.html.twig', [
			'controller_name' => 'IndexController',
			'classBody' 		=> $class
		]);
	}

	/**
	 * @Route("/", name="app_index")
	 */
	public function index(DoctorRepository $doctorRepository, PaginatorInterface $paginator, Request $request, UrlHelper $urlHelper, AppointmentRepository $appointmentRepository)
	{
		$this->cityArray = [
			"Meyrin",
			"Vernier",
			"Onex",
			"Plan-les-Ouates",
			"Lancy",
			"Le Grand-Saconnex",
			"Carouge",
			"Versoix",
			"Veyrier",
			"Chêne-Bougeries",
			"Thônex",
			"Nyon",
			"Gland",
			"Morges",
			"Ecublens",
			"Renens",
			"Prilly",
			"Lausanne",
			"Yverdon-les-Bains",
			"Pully",
			"Le Locle",
			"La Chaux-de-Fonds",
			"Vevey",
			"La Tour-de-Peilz",
			"Montreux",
			"Monthey",
			"Bulle",
			"Martigny",
			"Villars-sur-Glâne",
			"Biel/Bienne",
			"Lyss",
			"Delémont",
			"Sion",
			"Grenchen",
			"Köniz",
			"Ittigen",
			"Muri bei Bern",
			"Ostermundigen",
			"Bolligen",
			"Sierre",
			"Allschwil",
			"Oberwil",
			"Münsingen",
			"Binningen",
			"Basel",
			"Münchenstein",
			"Burgdorf",
			"Thun",
			"Birsfelden",
			"Riehen",
			"Steffisburg",
			"Muttenz",
			"Spiez",
			"Pratteln",
			"Herzogenbuchsee",
			"Liestal",
			"Langenthal",
			"Sumiswald",
			"Rheinfelden",
			"Visp",
			"Olten",
			"Oftringen",
			"Zofingen",
			"Bad Säckingen",
			"Brig",
			"Aarau",
			"Laufenburg",
			"Lenzburg",
			"Brugg",
			"Sarnen",
			"Mellingen",
			"Wohlen",
			"Kriens",
			"Emmen",
			"Baden",
			"Horw",
			"Wettingen",
			"Bremgarten",
			"Ebikon",
			"Stans",
			"Dietikon",
			"Rotkreuz",
			"Küssnacht",
			"Schlieren",
			"Affoltern am Albis",
			"Cham",
			"Regensdorf",
			"Arth",
			"Adliswil",
			"Baar",
			"Oberarth",
			"Bülach",
			"Goldau",
			"Thalwil",
			"Glattbrugg",
			"Opfikon",
			"Küsnacht",
			"Kloten",
			"Wallisellen",
			"Zollikon",
			"Horgen",
			"Dübendorf",
			"Neuhausen am Rheinfall",
			"Brüttisellen",
			"Meilen",
			"Wädenswil",
			"Effretikon",
			"Volketswil",
			"Richterswil",
			"Uster",
			"Illnau",
			"Winterthur",
			"Stäfa",
			"Einsiedeln",
			"Pfäffikon",
			"Locarno",
			"Wetzikon",
			"Rapperswil",
			"Jona",
			"Rüti",
			"Frauenfeld",
			"Gravesano",
			"Lugano",
			"Mendrisio",
			"Bellinzona",
			"Wil",
			"Wattwil",
			"Weinfelden",
			"Uzwil",
			"Kreuzlingen",
			"Münsterlingen",
			"Gossau",
			"Herisau",
			"Amriswil",
			"Engelburg",
			"St.Gallen",
			"Romanshorn",
			"Appenzell",
			"Arbon",
			"Buchs",
			"Chur",
			"Altstätten",
			"Davos",
			"Aargau",
			"Appenzell Ausserrhoden",
			"Appenzell Innerrhoden",
			"Basel-Landschaft",
			"Basel-Stadt",
			"Bern",
			"Fribourg",
			"Geneva",
			"Glarus",
			"Graubünden",
			"Jura",
			"Luzern",
			"Neuchâtel",
			"Nidwalden",
			"Obwalden",
			"Sankt Gallen",
			"Schwyz",
			"Schaffhausen",
			"Solothurn",
			"Thurgau",
			"Ticino",
			"Uri",
			"Valais",
			"Vaud",
			"Zug",
			"Zürich"
			
		];
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

		$isDoctor = false;
		if ($user) {
			$roles = $user->getRoles();
			if (in_array('ROLE_DOCTOR', $roles)) {
				$isDoctor = true;
			}
		}

		$options_other = [];
		$list_doctors = $doctorRepository->findAll();
		foreach ($list_doctors as $doctor) {
		    continue;
			$timingDB = $doctor->getIdTiming();
			$next_available = [];
			foreach ($timingDB as $time) {
			    continue;
				$day = $time->getDay();
				$year = $time->getYear();
				$month = $time->getMonth();
				$times = json_decode($time->getTimes(), true);
				foreach ($times as $t) {
					$exisAppointment = $appointmentRepository->findExistAppointment($year . '-' . $month . '-' . $day, $t['time']);
					$dt = date('y-m-d H:i', strtotime($year . '-' . $month . '-' . $day . ' ' .  $t['time']));
					$datetime = \DateTime::createFromFormat('y-m-d H:i', $dt);
					$now = new \DateTime('now');
					if ($t['selected'] && empty($exisAppointment) && $now<=$datetime) {
						$next_available[] = [
							'day'	=>	$day,
							'year'	=>	$year,
							'month'	=>	$month,
							'time'	=>	$t['time'],
							'date'  =>  $dt
						];
					}
				}
			}
			if ($next_available) {
				usort($next_available, function( $a, $b ) {
					return strtotime($a["date"]) - strtotime($b["date"]);
				});
				$next = $next_available[0];
				//foreach ($next_available as $next) {
				$dt = date('y-m-d H:i', strtotime($next['year'] . '-' . $next['month'] . '-' . $next['day'] . ' ' .  $next['time']));
				$str_time = explode(':', $next['time']);
				$time_seconds = $str_time[0] * 3600 + $str_time[1] * 60;
				//echo $doctor->getId() . ' ** ' . $dt . ' | ';
				$filter_all_doctors[] = [ 
					'date' 	=> $dt,
					'id'	=> $doctor->getId()
				];
				//}
			}
		}
		//Fields search
		$q = $request->request->get('search-field-q');
		$filter_address = $request->request->get('filter_address');
		$filter_distance = $request->request->get('filter_distance');
		$where_arr = [];
		
		if($q){
			$where_arr[] = '(a.first_name LIKE \'%' . $q . '%\' OR a.last_name LIKE \'%' . $q . '%\')';
		}
		$filter_address_maps = '';
		if($filter_address){
			foreach($filter_address as $d){
				if(empty($d)){
					continue;
				}
				$val = $d;
				if(strpos($d, '|') !== false){
					$term = explode('|', $d);
					$val = $term[1];
				}
				$filter_address_maps = $val . ' Switzerland';
				//$where_arr[] = "a.city = '$val' OR a.state = '$val' OR a.address_line_1 LIKE '%$val%' OR a.address_line_2 LIKE '%$val%'";
			}
		}
		if ($request->request->get('gen')) {
			$gen_array = $request->request->get('gen');
			foreach ($gen_array as &$arr) {
				$arr = "'" . $arr . "'";
			}
			$where_arr[] = 'a.gender IN (' .  implode(',', $gen_array) . ')';
		}
		if ($request->request->get('spec')) {
			$spec_array = $request->request->get('spec');
			foreach ($spec_array as &$arr) {
				$arr = "'" . $arr . "'";
			}
			$where_arr[] = 'a.speciality IN (' .  implode(',', $spec_array) . ')';
		}
		
		if ($request->request->get('lang_other')) {
			$lang_other = $request->request->get('lang_other');
			foreach ($lang_other as &$arr) {
				$arr = "'" . $arr . "'";
			}
			$where_arr[] = 'a.lang_other IN (' .  implode(',', $lang_other) . ')';
		}
		
		
		if ($request->request->get('lang')) {
			$langs = $request->request->get('lang');
			$w = [];
			foreach ($langs as $lang) {
				$w[] = "a.spoken_languages LIKE 'a:_:%" . $lang . "%'";
			}
			$where_arr[] = '(' . implode(' OR ', $w) . ')';
		}

		$where = '';
		if (!empty($where_arr)) {
			$where = ' WHERE ' . implode(' AND ', $where_arr);
		}
		$sql = '';
		$orderBy = '';
		$having= '';
		if ($request->query->get('dsort') || $filter_distance) {
			$client = HttpClient::create();
			$response = $client->request('GET', 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
			$resp_json = $response->getContent();
			$data = json_decode($resp_json, true);
			$lat = $data['lat'];
			$long = $data['lon'];
			if($filter_distance){
				$client = HttpClient::create();
				$adresse = '';
				$response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $filter_address_maps) . '&destination=' . str_replace(' ', '+', $filter_address_maps) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
				$content = $response->getContent();
				$localisation = json_decode($content, true);
				//var_dump($localisation);
				$lat = isset($localisation['routes'][0]['bounds']['northeast']['lat']) ? $localisation['routes'][0]['bounds']['northeast']['lat'] : 48.8504195;
				$long=isset($localisation['routes'][0]['bounds']['northeast']['lng']) ? $localisation['routes'][0]['bounds']['northeast']['lng'] : 2.2899323;
				$having = ' HAVING distance < ' . $filter_distance;
			}
			$sql = sprintf(', ROUND(6353 * 2 * ASIN(SQRT( POWER(SIN((%s - abs(a.latitude)) * pi()/180 / 2),2) + COS(%s * pi()/180 ) * COS( abs(a.latitude) *  pi()/180) * POWER(SIN((%s - a.longitude) *  pi()/180 / 2), 2) )), 2)   AS distance', $lat, $lat, $long, $lat);  
	  
	  
	 /* (6371 * acos( cos( radians(a.latitude) ) * cos( radians( %s ) ) * cos( radians( %s ) - radians(a.longitude) ) + sin( radians(a.latitude) ) * sin( radians( %s ) )) ) AS distance', $lat, $long, $lat);*/
			$orderBy = ' ORDER BY distance ASC';
		} 
		else if($request->query->get('psort')){
			$orderBy = ' ORDER BY a.price_custom_value ' . ucwords($request->query->get('psort'));
		}
		else if($request->query->get('svsort')){
		    $filter_all_doctors = $filter_all_doctors??[];
			usort($filter_all_doctors, function( $a, $b ) {
				return strtotime($a["date"]) - strtotime($b["date"]);
			});
			$ids = [];
			foreach($filter_all_doctors as $id){
				$ids[] = $id['id'];
			}
			$reversed = array_reverse($ids);
			if(!empty($reversed))
			    $orderBy = ' ORDER BY FIELD(a.id,' . implode(',', $reversed) . ') DESC';
		}
		
		$dql   = "SELECT a" . $sql . " FROM App:Doctor a" . $where . $having . $orderBy;
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery($dql);
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1), /*page number*/
			10 /*limit per page*/ ,
			[ 'wrap-queries'	=>	true	]
		);
		$allDoctors =  [];
		$limit = 1;
		foreach ($pagination as $doctor) {
			//var_dump($doctor);die;
			if(is_array($doctor)){
				$doctor = $doctor[0];
			}
			if($limit>10){
				break;
			}
			if($doctor->getLangOther()){
				$options_other[$doctor->getLangOther()] = $doctor->getLangOther();
			}
			$timingDB = $doctor->getIdTiming();
			$next_available = [];
			$next_available_string = '--:--';
			foreach ($timingDB as $time) {
				$day = $time->getDay();
				$year = $time->getYear();
				$month = $time->getMonth();
				$times = json_decode($time->getTimes(), true);
				foreach ($times as $t) {
					if ($t['selected'] == 'true') {
						$dt = date('y-m-d H:i', strtotime($year . '-' . $month . '-' . $day . ' ' .  $t['time']));
						$datetime = \DateTime::createFromFormat('y-m-d H:i', $dt);
						if ($datetime) {
							$next_available[$datetime->getTimestamp()] = [
								'day'	=>	$day,
								'year'	=>	$year,
								'month'	=>	$month,
								'time'	=>	$t['time']
							];
						}
					}
				}
			}
			if ($next_available) {
				ksort($next_available);
				foreach ($next_available as $next) {
					$now = new \DateTime('now');
					$dt = date('y-m-d H:i', strtotime($next['year'] . '-' . $next['month'] . '-' . $next['day'] . ' ' .  $next['time']));
					$datetime = \DateTime::createFromFormat('y-m-d H:i', $dt);
					if($datetime<$now){
						continue;
					}
					$next_available_string = 'Available on , ' . $datetime->format('D') . ' ' . $next['day'] . ' ' . $datetime->format('M') . ' ' . $next['year'] . ' at ' . $next['time'];
					break;
				}
			}
			$allDoctors[] = [
				'id'				=>	$doctor->getId(),
				'doc_name'			=>	$doctor->getTitle() . ' ' . $doctor->getFirstName() . ' ' . $doctor->getLastName(),
				'speciality'		=>	$doctor->getSpeciality(),
				'address'			=>	$doctor->getFormattedAddress(),
				'next_available'	=>	$next_available_string,
				'amount'			=>	$doctor->getPriceCustomValue() . ' CHF / Visit',
				'lat'				=>	$doctor->getLatitude() ? $doctor->getLatitude() : 48.8504195,
				'lng'				=>	$doctor->getLongitude() ? $doctor->getLongitude() : 2.2899323,
				'icons'				=>	'default',
				'profile_link'		=>	$this->generateUrl('app_doctor_profile', ['slug' => $doctor->getSlug()]),
				'image'				=> ($doctor->getPictureProfile()) ? $urlHelper->getAbsoluteUrl($doctor->getPictureProfile()) :  '/assets/img/doctor-default.jpg'
			];
			if($limit<10){
				$limit++;
			}
		}
		$doctors_array_to_json = [];
		foreach($list_doctors as $doctor){
			$doctors_array_to_json[] = [
				$doctor->getId(),
				$doctor->getEmail(),
				$doctor->getFirstName(),
				$doctor->getLastName(),
				$doctor->getGender(),
				$doctor->getPhoneNumber(),
				$doctor->getDateBirth(),
				$doctor->getAboutMe(),
				$doctor->getAddressLine1(),
				$doctor->getAddressLine2(),
				$doctor->getCity(),
				$doctor->getState(),
				$doctor->getCountry(),
				$doctor->getPostalCode(),
				$doctor->getPictureProfile(),
				$doctor->getReceivingPatientInfo(),
				$doctor->getServices(),
				$doctor->getSpecialization(),
				$doctor->getCreateAt(),
				$doctor->getUpdatedAt(),
				$doctor->getPriceCustomValue(),
				$doctor->getPriceType(),
				$doctor->getUsername(),
				$doctor->getRoles(),
				$doctor->getPassword(),
				$doctor->getSalt(),
				$doctor->getDoctorSocial(),
				$doctor->getIdTiming(),
				$doctor->getIdClinic(),
				$doctor->getEducation(),
				$doctor->getExperience(),
				$doctor->getAwards(),
				$doctor->getRegistrations(),
				$doctor->getAppointments(),
				$doctor->getSpeciality(),
				$doctor->getBusinessHours(),
				$doctor->getBusinessHoursArray(),
				$doctor->getLatitude(),
				$doctor->getLongitude(),
				$doctor->getUrlProfile(),
				$doctor->getSlug(),
				$doctor->getSpokenLanguages(),
				$doctor->getTitle(),
				$doctor->getFormattedAddress(),
				$doctor->getFormattedAddress2(),
				$doctor->getLangOther(),
				$doctor->getDisplayLanguage()
			];
		}
		$class = '';
		return $this->render('index/index.html.twig', [
			'controller_name' 		=> 'IndexController',
			'classBody' 			=> $class,
			'allDoctors' 			=> $allDoctors,
			'allDoctorsList' 		=> $doctorRepository->findAll(),
			'pagination'			=> $pagination,
			'spec'					=> $request->request->get('spec'),
			'gender'				=> $request->request->get('gen'),
			'language_other'		=> $request->request->get('lang'),
			'options_other'			=> $options_other,
			'lang_oth'				=> $request->request->get('lang_other'),
			'isDoctor'				=> $isDoctor,
			'q'						=> $q,
			'filter_address'		=> ($filter_address) ? $filter_address : [],
			'filter_distance'		=>  $filter_distance,
			'filter_zipcode'		=>	/*$data_location['zipcode']*/ [],
			'filter_place'			=>	$this->cityArray,
			'filter_state'			=>	[],
			'filter_state_code'		=>	/*$data_location['state_code']*/ [],
			'filter_province'		=>	/*$data_location['province']*/ [],
			'filter_province_code'	=>	/*$data_location['province_code']*/ [],
			'filter_community'		=>	/*$data_location['community']*/ [],
			'filter_community_code'	=>	/*$data_location['community_code']*/ [],
			'doctors_array_to_json'	=>	$doctors_array_to_json
		]);
	}
	public function formatHeaderTiming($dt, $isPrev = false)
	{
		if (!$dt) {
			$date = new \DateTime();
			$next_date = new \DateTime();
			$prev_date = new \DateTime();
		} else {
			$datef = date('m/d/Y h:i:s', $dt);
			$date = new \DateTime($datef);
			$next_date = new \DateTime();
			$next_date->setISODate($next_date->format('o'), $date->format('W') + 1);
			$prev_date = new \DateTime();
			$prev_date->setISODate($prev_date->format('o'), $date->format('W') - 1);
		}
		$weekStartEnd = $this->getStartAndEndDate($date->format('W'), $date->format('Y'));
		$current = strtotime($weekStartEnd['week_start']['year'] . '-' . $weekStartEnd['week_start']['month'] . '-' . $weekStartEnd['week_start']['day']);
		$date2 = strtotime($weekStartEnd['week_end']['year'] . '-' . $weekStartEnd['week_end']['month'] . '-' . $weekStartEnd['week_end']['day']);
		$stepVal = '+1 day';
		$response = [];
		while ($current <= $date2) {
			$dt = date('d-m-Y', $current);
			$dateObj   = new \DateTime($dt);
			$response['time'][] = [
				$dateObj->format('j'),
				$dateObj->format('m'),
				$dateObj->format('y')
			];
			$current = strtotime($stepVal, $current);
		}
		$response['next'] = $next_date;
		$response['prev'] = $prev_date;
		return $response;
	}
	public function getStartAndEndDate($week, $year)
	{
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
	public function getMaxElem($arr)
	{
		$count_arr = [];
		foreach ($arr as $a) {
			$times = json_decode($a->getTimes(), true);
			if (count($times)) {
				$index = 0;
				foreach ($times as $t) {
					if ($t['selected'] == 'true') {
						$index++;
					}
				}
				$count_arr[] = $index;
			} else {
				$count_arr[] = 0;
			}
		}
		$ret = 0;
		if (!empty($count_arr)) {
			$ret = max($count_arr);
		}
		return $ret;
	}
	public function isPassedDate($app_date, $app_time)
	{
		$dto = new \DateTime();
		$dto1 = new \DateTime($app_date . ' ' . $app_time);
		//echo $app_date . ' ' . $app_time . '<br>';
		$is_show = false;
		if ($dto <= $dto1) {
			$is_show = true;
		}
		return $is_show;
	}
	public function isUsed($app_date, $app_time)
	{
		$app_date = date('Y-m-d', strtotime($app_date));
		$em = $this->getDoctrine()->getManager();
		$appointments = $em->getRepository(Appointment::class)->findExistAppointment($app_date, $app_time);
		if ($appointments) {
			return true;
		}
		return false;
	}
	/**
	 * @Route("/ajax/next-prev", name="app_next_prev-ajax")
	 */
	public function nextPrev(Request $request, DoctorRepository $doctorRepository)
	{
		$doctor_id = $request->query->get('doctor_id');
		$type = $request->query->get('type');
		$doctor = $doctorRepository->find($doctor_id);
		$time_stamp = null;
		if ($request->query->get('next')) {
			$time_stamp = $request->query->get('next');
		} else if ($request->query->get('prev')) {
			$time_stamp = $request->query->get('prev');
		}
		$is_next_prev = false;
		if ($request->query->get('next')) {
			$is_next_prev = false;
		} else if ($request->query->get('prev')) {
			$is_next_prev = true;
		}
		$timing = $this->formatHeaderTiming($time_stamp, $is_next_prev);
		$global_array = $timing['time'];
		$array = $doctor->getIdTiming()->filter(
			function ($entry) use ($global_array) {
				$exist = false;
				foreach ($global_array as $tms) {
					if ($tms[0] == $entry->getDay() && $entry->getMonth() == $tms[1] && $entry->getYear() == $tms[2]) {
						$exist = true;
					}
				}
				return $exist;
			}
		);
		$slot = 0;
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
		$output[] = '<a class="dlproPrev" href onClick="return dlproPrev(this, ' . $timing['prev']->getTimestamp() . ',' . $doctor->getId() . ');">' . "\n";
		$output[] = '<i class="fa fa-chevron-left"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		foreach ($timing['time'] as $time) {
			$date = $time[0] . '-' .  $time[1] . '-' .  $time[2];
			$dateObj   = \DateTime::createFromFormat('j-m-Y', $date);
			$monthName = $dateObj->format('F');
			$dayName = $dateObj->format('D');
			$output[] = '<li>' . "\n";
			$output[] = '<span>' . $dayName . '</span>' . "\n";
			$output[] = '<span class="slot-date">' . $time[0] . ' ' . $monthName . ' <small class="slot-year">' . $time[2] . '</small></span>' . "\n";
			$output[] = '</li>' . "\n";
		}
		$output[] = '<li class="right-arrow">' . "\n";
		$output[] = '<a class="dlproNext" href onClick="return dlproNext(this, ' . $timing['next']->getTimestamp() . ',' . $doctor->getId() . ');">' . "\n";
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
		$output[] = '<ul class="clearfix dlproCollapse" id="collapseDlproTime' . $doctor->getId() . '">' . "\n";
		if (!empty($array)) {
			$max_el = $this->getMaxElem($array);
			foreach ($array as $tm) {
				$day = $tm->getDay();
				$year = $tm->getYear();
				$month = $tm->getMonth();
				$times = json_decode($tm->getTimes(), true);
				$first = true;
				//echo $day . '/' . $month . '/' . $year . 'ednd1 - <br>';
				$output[] = '<li>' . "\n";
				if (count($times)) {
					$count_selected = 0;
					foreach ($times as $t) {
						$selected = '';
						$app_date = $year . '-' . $month . '-' . $day;
						$app_time = $t['time'];
						/*if($t['selected'] == 'true'){
							$selected= 'selected';
							$output[] = '<a class="timing dlproTimeElement ' . $selected . ' dlproTimeElementSelected" data-doctorid="' . $doctor->getId() . '" href data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span> ' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}*/
						if ($t['selected'] == 'true' && !$this->isUsed($app_date, $app_time) && $this->isPassedDate($app_date, $app_time)) {
							$selected = 'selected';
							$output[] = '<a class="timing dlproTimeElementSelected ' . $selected . '" href data-doctorid="' . $doctor->getId() . '" data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
						if ($t['selected'] == 'true' && !$this->isUsed($app_date, $app_time) && !$this->isPassedDate($app_date, $app_time)) {
							$selected = 'selected';
							$output[] = '<a class="timing dlproTimeElement" href data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '" onClick="return false;">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
						if ($t['selected'] == 'true' && $this->isUsed($app_date, $app_time) && !$this->isPassedDate($app_date, $app_time)) {
							$selected = 'selected';
							$output[] = '<a class="timing dlproTimeElement" href data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '" onClick="return false;">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
					}
					if ($count_selected) {
						for ($i = 0; $i < ($max_el - $count_selected); $i++) {
							$output[] = '<a class="timing dlproTimeElement" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
							$output[] = '<span>--:--</span> ' . "\n";
							$output[] = '</a>' . "\n";
						}
					} else {
						for ($i = 0; $i < $max_el; $i++) {
							$output[] = '<a class="timing dlproTimeElement" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
							$output[] = '<span>--:--</span> ' . "\n";
							$output[] = '</a>' . "\n";
						}
					}
					$output[] = '</li>' . "\n";
				} else {
				}
			}
		} else {
			foreach ($timing['time'] as $time) {
				$output[] = '<li>' . "\n";
				for ($j = 25200; $j <= 72000; $j = $j + $slot) {
					$h = floor($j / 3600);
					$m = floor($j % 3600 / 60);
					$output[] = '<a class="timing dlproTimeElement" href="#" data-step="' . $h . '" data-time="' . $h . ':' . $m . '" data-id="" data-year="' . $time[2] . '" data-month="' . $time[1] . '" data-day="' . $time[0] . '">' . "\n";
					$output[] = '<span>' . $h . ':' . $m . '</span> ' . "\n";
					$output[] = '</a>' . "\n";
				}
				$output[] = '</li>' . "\n";
			}
		}
		$output[] = '</ul>' . "\n";
		$output[] = '<div class="col-md-12 text-center is-dlpro-show-more" style="padding-top: 15px;">' . "\n";
		$output[] = '<a class="btn btn-outline-primary dlproColapseLink" href="#collapseDlproTime' . $doctor->getId() . '">See more schedules</a>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Time Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Content -->' . "\n";
		return new Response(join("\n", $output));
	}
}
