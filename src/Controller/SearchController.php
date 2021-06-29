<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Repository\DoctorRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/prefetch", name="app_search_prefetch")
     */
    public function prefetch(DoctorRepository $doctorRepository)
    {
        $doctors = $doctorRepository->findAll();
        $doctors_array = [];
        foreach ($doctors as $doctor) {
            /*$doctors_array[] = [
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
            ];*/
        }
        $response = new JsonResponse($doctors_array);
        return $response;
    }

    /**
     * @Route("/search", name="app_search")
     */
    public function search(DoctorRepository $doctorRepository, PaginatorInterface $paginator, Request $request, UrlHelper $urlHelper)
    {
        //Fields search
        $q = $request->request->get('search-doctor-field-q');
        $location = $request->request->get('search-location');

        $user = $this->getDoctorFromSession();

        $isDoctor = false;
        if ($user) {
            $roles = $user->getRoles();
            if (in_array('ROLE_DOCTOR', $roles)) {
                $isDoctor = true;
            }
        }

        $addTimeToListDoctors = $this->addTimeToListDoctors($doctorRepository, $urlHelper);
        $allDoctors = $addTimeToListDoctors['allDoctors'];
        $options_other = $addTimeToListDoctors['options_other'];

        $where_arr = [];
        if ($q) {
            $where_arr[] = '(a.first_name LIKE \'%' . $q . '%\' OR a.last_name LIKE \'%' . $q . '%\')';
        }
        $loc_near_enabled = true;
        $new_location = [];
        if ($location) {
            $origin_destination = str_replace(' ', '+', $location);
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . $origin_destination . '&destination=' . $origin_destination . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
            $resp_json = $response->getContent();
            $data = json_decode($resp_json, true);
            $new_location['lat'] = $data['routes'][0]['bounds']['northeast']['lat'];
            $new_location['long'] = $data['routes'][0]['bounds']['northeast']['lng'];
        }
        if ($request->request->get('gen')) {
            $gen_array = $request->request->get('gen');
            foreach ($gen_array as &$arr) {
                $arr = "'" . $arr . "'";
            }
            $where_arr[] = 'a.gender IN (' . implode(',', $gen_array) . ')';
        }
        if ($request->request->get('spec')) {
            $spec_array = $request->request->get('spec');
            foreach ($spec_array as &$arr) {
                $arr = "'" . $arr . "'";
            }
            $where_arr[] = 'a.speciality IN (' . implode(',', $spec_array) . ')';
        }

        if ($request->request->get('lang_other')) {
            $lang_other = $request->request->get('lang_other');
            foreach ($lang_other as &$arr) {
                $arr = "'" . $arr . "'";
            }
            $where_arr[] = 'a.lang_other IN (' . implode(',', $lang_other) . ')';
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
        if ($request->query->get('dsort') || $loc_near_enabled) {
            $client = HttpClient::create();
            $response = $client->request('GET', 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
            $resp_json = $response->getContent();
            $data = json_decode($resp_json, true);
            $lat = $data['lat'];
            $long = $data['lon'];
            if (!empty($new_location)) {
                $lat = $data['lat'];
                $long = $data['lon'];
            }
            $sql = sprintf(', (6371 * acos( cos( radians(a.latitude) ) * cos( radians( %s ) ) * cos( radians( %s ) - radians(a.longitude) ) + sin( radians(a.latitude) ) * sin( radians( %s ) )) ) AS distance', $lat, $long, $lat);
            $orderBy = ' ORDER BY distance ASC';
        } else if ($request->query->get('psort')) {
            $orderBy = ' ORDER BY a.price_custom_value ' . ucwords($request->query->get('psort'));
        } else {
            $orderBy = ' ORDER BY a.id ASC';
        }
        $dql = "SELECT a" . $sql . " FROM App:Doctor a" . $where . $orderBy;
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $class = '';
        return $this->render('search/search.html.twig', [
            'controller_name' => 'IndexController',
            'classBody' => $class,
            'pagination' => $pagination,
            'allDoctors' => $allDoctors,
            'allDoctorsList' => $doctorRepository->findAll(),
            'spec' => $request->request->get('spec'),
            'gender' => $request->request->get('gen'),
            'language_other' => $request->request->get('lang'),
            'options_other' => $options_other,
            'lang_oth' => $request->request->get('lang_other'),
            'isDoctor' => $isDoctor,
            'q' => $q,
            'search_location' => $location
        ]);
    }

    public function getDoctorFromSession()
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
        return $user;
    }

    public function addTimeToListDoctors($doctorRepository, $urlHelper)
    {
        $options_other = [];
        $allDoctors = [];
        $limit = 1;
        foreach ($doctorRepository->findAll() as $doctor) {
            if ($limit > 10) {
                break;
            } else {
                $limit++;
            }
            $options_other[$doctor->getLangOther()] = $doctor->getLangOther();
            $timingDB = $doctor->getIdTiming();
            $next_available = [];
            foreach ($timingDB as $time) {
                $day = $time->getDay();
                $year = $time->getYear();
                $month = $time->getMonth();
                $times = json_decode($time->getTimes(), true);
                foreach ($times as $t) {
                    if ($t['selected'] == 'true') {
                        $dt = date('y-m-d H:i', strtotime($year . '-' . $month . '-' . $day . ' ' . $t['time']));
                        $datetime = DateTime::createFromFormat('y-m-d H:i', $dt);
                        if ($datetime) {
                            $next_available[$datetime->getTimestamp()] = [
                                'day' => $day,
                                'year' => $year,
                                'month' => $month,
                                'time' => $t['time']
                            ];
                        }
                    }
                }
            }
            $next_available_string = '--:--';
            if ($next_available) {
                ksort($next_available);
                foreach ($next_available as $next) {
                    $dt = date('y-m-d H:i', strtotime($next['year'] . '-' . $next['month'] . '-' . $next['day'] . ' ' . $next['time']));
                    $datetime = DateTime::createFromFormat('y-m-d H:i', $dt);
                    $next_available_string = 'Available on , ' . $datetime->format('D') . ' ' . $next['day'] . ' ' . $datetime->format('M') . ' ' . $next['year'] . ' at ' . $next['time'];
                    break;
                }
            }
            $allDoctors[] = [
                'id' => $doctor->getId(),
                'doc_name' => $doctor->getTitle() . ' ' . $doctor->getFirstName() . ' ' . $doctor->getLastName(),
                'speciality' => $doctor->getSpeciality(),
                'address' => $doctor->getFormattedAddress(),
                'next_available' => $next_available_string,
                'amount' => $doctor->getPriceCustomValue() . ' CHF / Visit',
                'lat' => $doctor->getLatitude() ? $doctor->getLatitude() : 48.8504195,
                'lng' => $doctor->getLongitude() ? $doctor->getLongitude() : 2.2899323,
                'icons' => 'default',
                'profile_link' => $this->generateUrl('app_doctor_profile', ['slug' => $doctor->getSlug()]),
                'image' => ($doctor->getPictureProfile()) ? $urlHelper->getAbsoluteUrl($doctor->getPictureProfile()) : '/assets/img/doctor-default.jpg'
            ];
        }
        return [
            'allDoctors' => $allDoctors,
            'options_other' => $options_other
        ];
    }

    /**
     * @Route("/search/find", name="app_search_find")
     */
    public function find(DoctorRepository $doctorRepository, Request $request)
    {
        $q = $request->query->get('q');
        $doctors = $doctorRepository->findBySearchField($q);
        //var_dump($doctors);die;
        $response = new JsonResponse($doctors);
        return $response;
    }
}
