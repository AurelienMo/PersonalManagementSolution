<?php

declare(strict_types=1);

/*
 * This file is part of PersonalManagementSolution
 *
 * (c) Aurelien Morvan <morvan.aurelien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Common\Subscribers;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Responders\JsonResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber.
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        switch (get_class($exception)) {
            case HttpException::class:
                $this->processHttpException($event, $exception);
                break;
            case ValidatorException::class:
                $this->processValidatorException($event, $exception);
                break;
        }
    }

    private function processValidatorException(GetResponseForExceptionEvent $event, ValidatorException $exception)
    {
        /** @var string $datas */
        $datas = json_encode($exception->getErrors());
        $event
            ->setResponse(
                JsonResponder::response(null, $datas, 400)
            );
    }

    private function processHttpException(GetResponseForExceptionEvent $event, HttpException $exception)
    {
        /** @var string $datas */
        $datas = json_encode(
            [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ]
        );
        $event
            ->setResponse(
                JsonResponder::response(null, $datas, $exception->getStatusCode())
            );
    }
}
