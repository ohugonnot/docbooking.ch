<?php

namespace App\Controller;

use App\Entity\Doctor;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportController extends AbstractController
{
    /**
     * @Route("/import/delete/{id}", name="app_import_delete")
     */
    public function import_delete(Doctor $doctor, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($doctor);
        $em->flush();
    }

    /**
     * @Route("/import/check", name="app_import_check")
     */
    public function import_check(UserPasswordEncoderInterface $passwordEncoder)
    {
        $csv = array_map('str_getcsv', file('/home/fapulavi/www/docbooking.ch/var/data10.csv'));
        unset($csv[0]);
        $array_duplicate = [];
        foreach ($csv as $c) {
            if (empty($c[2])) {
                continue;
            }
            if (!empty($c[5])) {
                $dql = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.email=:email";
                $em = $this->getDoctrine()->getManager();
                $qy = $em->createQuery($dql);
                $qy->setParameter('email', $c[5]);
                $query = $qy->getOneOrNullResult();
                $array_duplicate[$c[5]] = $query['NumHits'];
            } else {
                //var_dump($c);
            }
        }
        var_dump($array_duplicate);
        die;
    }

    /**
     * @Route("/import", name="app_import")
     */
    public function index(UserPasswordEncoderInterface $passwordEncoder)
    {
        $csv = array_map('str_getcsv', file('/home/fapulavi/www/docbooking.ch/var/data10.csv'));
        unset($csv[0]);
        $array_duplicate = [];
        foreach ($csv as $c) {
            if (empty($c[2])) {
                continue;
            }
            //var_dump($c);
            if (!empty($c[5])) {
                $dql = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.email=:email";
                $em = $this->getDoctrine()->getManager();
                $qy = $em->createQuery($dql);
                $qy->setParameter('email', $c[5]);
                $query = $qy->getOneOrNullResult();
                $array_duplicate[$c[5]] = $query['NumHits'];
            } else {
                //var_dump($c);
            }
        }
        foreach ($csv as $c) {
            try {
                if ($array_duplicate[$c[5]] == 1) {
                    continue;
                }
                $doctor = new Doctor();
                if (!empty($c[40])) {
                    $doctor->setTitle($c[40]);
                }
                $doctor->setFirstName($c[2]);
                $doctor->setLastName($c[3]);
                if ($c[4] == 'M') {
                    $doctor->setGender('Male');
                } else if ($c[4] == 'F') {
                    $doctor->setGender('Female');
                }
                $doctor->setEmail($c[5]);
                $doctor->setPassword($passwordEncoder->encodePassword(
                    $doctor,
                    $c[6]
                ));
                if ($c[7]) {
                    $doctor->setPhoneNumber($c[7]);
                } else if ($c[8]) {
                    $doctor->setPhoneNumber($c[8]);
                } else if ($c[9]) {
                    $doctor->setPhoneNumber($c[9]);
                }
                if (!empty($c[10])) {
                    $doctor->setDateBirth($c[10]);
                }
                if (!empty($c[11])) {
                    $doctor->setAboutMe($c[11]);
                }
                $address_line_1 = '';
                if (!empty($c[12])) {
                    $doctor->setAddressLine1($c[12]);
                    $address_line_1 = $c[12];
                }
                $address_line_2 = '';
                if (!empty($c[13])) {
                    $doctor->setAddressLine2($c[13]);
                    $address_line_2 = $c[13];
                }
                $city = '';
                if (!empty($c[14])) {
                    $city = $c[14];
                    $doctor->setCity($c[14]);
                }
                if (!empty($c[15])) {
                    $doctor->setState($c[15]);
                }
                if (!empty($c[16])) {
                    $doctor->setCountry($c[16]);
                }
                if (!empty($c[17])) {
                    $doctor->setPostalCode($c[17]);
                }

                $state = $c[15];
                $country = $c[16];
                $postal_code = $c[17];
                if (!empty($address_line_1) || !empty($address_line_2) || !empty($city) || !empty($state) || !empty($country) || !empty($postal_code)) {
                    $adresse = $address_line_2 . ' ' . $city . ' ' . $city . ' ' . $country . ' ' . $postal_code;
                    $client = HttpClient::create();
                    $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $adresse) . '&destination=' . str_replace(' ', '+', $adresse) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
                    $content = $response->getContent();
                    $localisation = json_decode($content, true);
                    //var_dump($localisation['routes'][0]['bounds']['northeast']['lat']);
                    $lat = isset($localisation['routes'][0]['bounds']['northeast']['lat']) ? $localisation['routes'][0]['bounds']['northeast']['lat'] : 48.8504195;
                    $lng = isset($localisation['routes'][0]['bounds']['northeast']['lng']) ? $localisation['routes'][0]['bounds']['northeast']['lng'] : 2.2899323;
                    $doctor->setState($city);
                    $doctor->setLatitude($lat);
                    $doctor->setLongitude($lng);
                }
                if (!empty($c[22])) {
                    $doctor->setSpeciality($c[22]);
                }
                $doctor->setServices($c[23]);
                $doctor->setSpecialization($c[24]);
                $doctor->setRoles(['ROLE_DOCTOR']);
                $SpokenLanguages = array();
                if (!empty($c[32]) == 1) {
                    $SpokenLanguages[] = 'French';
                }
                if (!empty($c[33]) == 1) {
                    $SpokenLanguages[] = 'German';
                }
                if (!empty($c[34]) == 1) {
                    $SpokenLanguages[] = 'Italien';
                }
                if (!empty($c[35]) == 1) {
                    $SpokenLanguages[] = 'English';
                }
                if (!empty($c[36]) == 1) {
                    $SpokenLanguages[] = 'Other';
                    $doctor->setLangOther('Spanish');
                }
                if (!empty($c[37]) == 1) {
                    $SpokenLanguages[] = 'Russian';
                }
                if (!empty($c[38]) == 1) {
                    $SpokenLanguages[] = 'Arabic';
                }
                if (!empty($c[39])) {
                    $SpokenLanguages[] = 'Other';
                    $doctor->setLangOther($c[39]);
                }
                $doctor->setSpokenLanguages($SpokenLanguages);
                $doctor->setLangOther(!empty($c[39]));

                $link = $doctor->getUrlProfile();
                $dql = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.slug LIKE :link";
                $em = $this->getDoctrine()->getManager();
                $qy = $em->createQuery($dql);
                $qy->setParameter("link", '%' . $link . '%');
                $query = $qy->getOneOrNullResult();
                $link = ($query['NumHits'] > 0) ? ($link . '-' . $query['NumHits']) : $link;
                $doctor->setSlug($link);

                $doctor->setReceivingPatientInfo($c[19]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($doctor);
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
            }

        }
    }

    /**
     * @Route("/fixSlug", name="app_fixSlug")
     */
    public function fixSlug(UserPasswordEncoderInterface $passwordEncoder)
    {
        $doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();
        foreach ($doctors as $doctor) {
            //var_dump($doctor);die;
            $link = $doctor->getUrlProfile();
            $slug = $doctor->getSlug();
            $dql = "SELECT COUNT(a) AS NumHits FROM App:Doctor a WHERE a.slug=:link AND a.id <> :id";
            $em = $this->getDoctrine()->getManager();
            $qy = $em->createQuery($dql);
            $qy->setParameter('link', $link);
            $qy->setParameter('id', $doctor->getId());
            $query = $qy->getOneOrNullResult();
            $link_new = ($query['NumHits'] > 0) ? ($link . '-' . $query['NumHits']) : $link;
            if ($slug !== $link) {
                $doctor->setSlug($link_new);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();
        }
    }

    /**
     * @Route("/fixLoc", name="app_fixLoc")
     */
    public function fixLoc(UserPasswordEncoderInterface $passwordEncoder)
    {
        $doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();
        foreach ($doctors as $doctor) {
            //var_dump($doctor);die;
            $client = HttpClient::create();
            $filter_address_maps = $doctor->getFormattedAddress2();
            $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/directions/json?origin=' . str_replace(' ', '+', $filter_address_maps) . '&destination=' . str_replace(' ', '+', $filter_address_maps) . '&key=AIzaSyB_fWsdvWvc9pHt-AyXFbl_vz7R7sjrco4');
            $content = $response->getContent();
            $localisation = json_decode($content, true);
            //var_dump($localisation);
            $lat = isset($localisation['routes'][0]['bounds']['northeast']['lat']) ? $localisation['routes'][0]['bounds']['northeast']['lat'] : 48.8504195;
            $long = isset($localisation['routes'][0]['bounds']['northeast']['lng']) ? $localisation['routes'][0]['bounds']['northeast']['lng'] : 2.2899323;
            $doctor->setLatitude($lat);
            $doctor->setLongitude($long);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();
        }
    }


    /**
     * @Route("/import2", name="app_import2")
     */
    public function index2(UserPasswordEncoderInterface $passwordEncoder)
    {
        $csv = array_map('str_getcsv', file('/home/fapulavi/www/docbooking.ch/var/data9.csv'));
        unset($csv[0]);
        foreach ($csv as $c) {
            //echo $c[2] . '|' . $c[12];
            if (empty($c[2])) {
                continue;
            }
            $doctor = $this->getDoctrine()->getRepository(Doctor::class)->findOneByEmailField($c[5]);
            if (!$doctor) {
                continue;
            }
            $SpokenLanguages = array();
            if (!empty($c[32]) == 1) {
                $SpokenLanguages[] = 'French';
            }
            if (!empty($c[33]) == 1) {
                $SpokenLanguages[] = 'German';
            }
            if (!empty($c[34]) == 1) {
                $SpokenLanguages[] = 'Italien';
            }
            if (!empty($c[35]) == 1) {
                $SpokenLanguages[] = 'English';
            }
            if (!empty($c[36]) == 1) {
                $SpokenLanguages[] = 'Other';
                $doctor->setLangOther('Spanish');
            }
            if (!empty($c[37]) == 1) {
                $SpokenLanguages[] = 'Russian';
            }
            if (!empty($c[38]) == 1) {
                $SpokenLanguages[] = 'Arabic';
            }
            if (!empty($c[39])) {
                $SpokenLanguages[] = 'Other';
                $doctor->setLangOther($c[39]);
            }
            $doctor->setSpokenLanguages($SpokenLanguages);
            if (!empty($c[15])) {
                $doctor->setState($c[15]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();
        }
    }
}
