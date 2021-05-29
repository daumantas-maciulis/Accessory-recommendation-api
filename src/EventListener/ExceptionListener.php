<?php


namespace App\EventListener;


use App\Exception\ExternalApiException;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $exceptionResponse = $this->getExceptionResponse($exception);

        $event->setResponse($exceptionResponse);
    }

    private function getExceptionResponse(\Throwable $exception)
    {
        //todo delete DUMP
        dump($exception);
        switch ($exception) {
            case($exception instanceof NotFoundHttpException):
                return $this->handleNotFoundHttpException();
            case($exception instanceof ClientException):
                return $this->handleExternalAPIExceptions($exception);
        }
    }

    private function handleNotFoundHttpException(): Response
    {
        $response = [
            'message' => 'Not Found',
            'code' => '404'
        ];

        return new JsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    private function handleExternalAPIExceptions($exception): Response
    {
        $response = [
            'message' => 'External API error',
            'code' => '500'
        ];

        return new JsonResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }





}