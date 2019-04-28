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

namespace App\Domain\CategoryTask\AddCat;

use App\Domain\AbstractPersister;
use App\Domain\Common\Factory\CategoryTaskFactory;
use App\Entity\CategoryTask;
use App\Repository\CategoryTaskRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /**
     * @param AddCatInput $input
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(AddCatInput $input): void
    {
        $catTask = CategoryTaskFactory::create($input->getName());
        /** @var CategoryTaskRepository $repo */
        $repo = $this->getRepository(CategoryTask::class);
        $repo->persistSave($catTask, true);
    }
}
