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

/**
 * Class CategoryTaskRepository
 */
class CategoryTaskRepository extends AbstractRepository
{
    /**
     * @param array $filter
     *
     * @return array
     */
    public function loadCat(?string $filter)
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
}
