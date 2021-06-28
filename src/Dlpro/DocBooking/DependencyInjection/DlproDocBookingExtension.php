<?php
namespace App\Dlpro\DocBooking\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DlproDocBookingExtension extends Extension implements PrependExtensionInterface
{	
    public function prepend(ContainerBuilder $container)
    {
		/*try{
			if(strpos($_SERVER['REQUEST_URI'], '/doctor/') !== false){
				echo 'doctor';
				$class = 'App\Repository\DoctorResetPasswordRequestRepository';
			}
			else{
				echo 'patient';
				$class='App\Repository\PatientResetPasswordRequestRepository';
			}*/
			/*$container->loadFromExtension('symfonycasts_reset_password', [
				'request_password_repository' => $class,
			]);*/
			/*$container->prependExtensionConfig('symfonycasts_reset_password', [
				'request_password_repository' => $class,
			]);*/
			//$container->setParameter('symfonycasts_reset_password.request_password_repository', $class);
		/*}catch(Exception $e){
			
		}*/
		//var_dump($container);
		//die;
    }
	public function load(array $configs, ContainerBuilder $container){
	}
}