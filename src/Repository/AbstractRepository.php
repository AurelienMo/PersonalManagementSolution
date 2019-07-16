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

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class AbstractRepository.
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct(
            $registry,
            $this->getClass()
        );
    }

    /**
     * @param AbstractEntity|null $entity
     * @param bool                $isNew
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persistSave(AbstractEntity $entity = null, bool $isNew = false)
    {
        if ($isNew) {
            $this->_em->persist($entity);
        }

        $this->_em->flush();
    }

    abstract protected function getClass(): string;
}
