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
use App\Domain\Common\Events\MailEvent;
use App\Domain\Common\Helpers\MailHelper;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailSubscriber
 */
class MailSubscriber extends AbstractMailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MailEvent::ON_REGISTRATION => 'sendMail',
        ];
    }

    /**
     * @param MailEvent $event
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendMail(MailEvent $event)
    {
        /** @var User $to */
        $to = $event->getTo();
        $this->mailHelper->sendMail(
            MailHelper::PARAMS_MAIL_APPLICATION,
            [
                'email' => $to->getEmail(),
                'name' => sprintf("%s %s", $to->getFirstname(), $to->getLastname()),
            ],
            'PMS - Confirmation d\'inscription',
            'mails/registration.html.twig',
            [
                'user' => $to,
            ]
        );
    }
}
