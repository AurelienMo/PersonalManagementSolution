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

namespace App\Common\Domain\Groups\Create;

use App\Common\Domain\Common\Factory\GroupFactory;
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
     * @param NewGroupInput $input
     *
     * @return string
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function save(NewGroupInput $input)
    {
        $group = GroupFactory::create(
            $input->getName(),
            password_hash($input->getPasswordToJoin(), PASSWORD_BCRYPT),
            $input->getOwner()
        );
        $input->getOwner()->defineGroup($group);
        $input->getOwner()->updateRole('ROLE_OWNER');
        $this->getManager('common')->getRepository(User::class)
                                            ->persistSave($group, true);

        return $this->serializer->serialize(
            $group,
            'json',
            [
                'groups' => [
                    'all',
                    'group_detail_show',
                ],
            ]
        );
    }
}
