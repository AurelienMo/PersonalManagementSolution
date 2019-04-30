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

namespace App\Repository;

use App\Entity\Group;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends AbstractRepository implements UserLoaderInterface
{
    /**
     * @param string $username
     *
     * @return mixed|UserInterface|null
     *
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.email = :identifier OR u.username = :identifier')
                    ->setParameter('identifier', $username)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param Group  $group
     * @param string $fullname
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function loadByFullNameInGroup(Group $group, string $fullname)
    {
        $fullNameExplode = explode(' ', $fullname);

        return $this->createQueryBuilder('u')
                    ->where('u.firstname = :firstname AND u.lastname = :lastname')
                    ->andWhere('u.group = :group')
                    ->setParameters(
                        [
                            'firstname' => str_replace(' ', '', $fullNameExplode[0]),
                            'lastname' => str_replace(' ', '', $fullNameExplode[1]),
                            'group' => $group
                        ]
                    )
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
