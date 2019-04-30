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

use App\Entity\CategoryTask;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class CategoryTaskRepository
 */
class CategoryTaskRepository extends AbstractRepository
{
    /**
     * @param string $filter
     *
     * @return array
     */
    public function loadCat(?string $filter): array
    {
        $qb = $this->createQueryBuilder('ct');
        if (!\is_null($filter)) {
            $qb->where('ct.name LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
        }

        $query = $qb->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param string $name
     *
     * @return CategoryTask|null
     *
     * @throws NonUniqueResultException
     */
    public function loadByName(string $name): ?CategoryTask
    {
        return $this->createQueryBuilder('ct')
                    ->where('ct.name LIKE :name')
                    ->setParameter('name', '%'.ucfirst($name))
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
