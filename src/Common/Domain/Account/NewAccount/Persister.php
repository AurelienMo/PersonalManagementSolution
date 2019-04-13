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

use App\Common\Domain\Common\Factory\UserFactory;
use App\Common\Entity\User;
use App\Globals\Domain\AbstractPersister;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /**
     * @param NewAccountInput $input
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function save(NewAccountInput $input)
    {
        $user = UserFactory::create(
            $input->getFirstname(),
            $input->getLastname(),
            $input->getUsername(),
            $input->getPassword(),
            $input->getEmail()
        );

        /** @ */
        $this->getManager('common')->getRepository(User::class)
                                            ->persistSave($user, true);

        $this->eventDispatcher->dispatch(
            MailSubscriptionEvent::USER_SUBSCRIPTION_MAIL,
            new MailSubscriptionEvent($user)
        );
    }
}
