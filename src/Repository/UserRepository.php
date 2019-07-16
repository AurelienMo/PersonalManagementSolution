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
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends AbstractRepository implements UserLoaderInterface
{
    protected function getClass(): string
    {
        return User::class;
    }

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
}
