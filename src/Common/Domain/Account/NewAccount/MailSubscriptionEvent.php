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

namespace App\Common\Domain\Account\NewAccount;

use App\Common\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MailSubscriptionEvent
 */
class MailSubscriptionEvent extends Event
{
    const USER_SUBSCRIPTION_MAIL = 'app.mails.subscription';

    /** @var User */
    protected $user;

    /**
     * MailSubscriptionEvent constructor.
     *
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
