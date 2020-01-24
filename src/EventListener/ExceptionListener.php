<?php
/**
 * Created by PhpStorm.
 * User: Amel
 * Date: 30/11/2019
 * Time: 21:43
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        if(!$event->getThrowable() instanceof NotFoundHttpException){
            return;
        }

        $response = new RedirectResponse($_ENV["BASE_ENDPOINT"]);
        $event->setResponse($response);
    }
}