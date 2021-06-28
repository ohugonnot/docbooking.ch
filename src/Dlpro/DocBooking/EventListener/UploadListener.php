<?php
namespace App\Dlpro\DocBooking\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
		$response['name'] = $event->getFile()->getBasename();
		return $response;
    }
}