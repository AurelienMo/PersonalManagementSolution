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

namespace App\Common\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class GroupRepository
 */
class GroupRepository extends EntityRepository
{
    /**
     * @param string $id
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function getGroupById(string $id)
    {
        $qb = $this->createQueryBuilder('g')
                   ->where('g.id = :id')
                   ->setParameter('id', $id);

        $query = $qb->getQuery();
        $query->useQueryCache(true)
            ->useResultCache(true, 60);

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $id
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function loadFullInformations(string $id)
    {
        $qb = $this->createQueryBuilder('g')
                   ->leftJoin('g.members', 'm')
                   ->where('g.id = :id')
                   ->setParameter('id', $id);

        $query = $qb->getQuery();
            $query->useQueryCache(true)
            ->useResultCache(true, 60);

        return $query->getOneOrNullResult();
    }
}
