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

namespace App\Domain\Common\Events;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MailEvent
 */
class MailEvent extends Event
{
    const ON_REGISTRATION = 'app.mails.on_registration';

    /** @var User|null */
    protected $from;

    /** @var User|null */
    protected $to;

    /**
     * MailEvent constructor.
     *
     * @param User|null $from
     * @param User|null $to
     */
    public function __construct(
        ?User $from,
        ?User $to
    ) {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return User|null
     */
    public function getFrom(): ?User
    {
        return $this->from;
    }

    /**
     * @return User|null
     */
    public function getTo(): ?User
    {
        return $this->to;
    }
}
