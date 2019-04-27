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

namespace App\Domain\Account\NewAccount;

use App\Domain\AbstractMailSubscriber;
use App\Domain\Common\Helpers\MailHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailSubscriptionSubscriber.
 */
class MailSubscriptionSubscriber extends AbstractMailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MailSubscriptionEvent::USER_SUBSCRIPTION_MAIL => 'onRegistration',
        ];
    }

    /**
     * @param MailSubscriptionEvent $event
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onRegistration(MailSubscriptionEvent $event)
    {
        $this->mailHelper->sendMail(
            MailHelper::PARAMS_MAIL_APPLICATION,
            [
                'email' => $event->getUser()->getEmail(),
                'name' => sprintf('%s %s', $event->getUser()->getFirstname(), $event->getUser()->getLastname()),
            ],
            'PMS - Confirmation d\'inscription',
            'common/mails/registration.html.twig',
            [
                'user' => $event->getUser(),
            ]
        );
    }
}
