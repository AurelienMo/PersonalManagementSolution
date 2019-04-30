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

namespace App\Domain\Common\Factory;

use App\Entity\CategoryTask;
use App\Entity\Task;
use App\Entity\User;

/**
 * Class TaskFactory
 */
class TaskFactory
{
    /**
     * @param string       $name
     * @param CategoryTask $categoryTask
     * @param User         $owner
     * @param bool         $displayInGroup
     * @param string       $startAt
     * @param string       $dueAt
     * @param User         $personAffected
     *
     * @return Task
     * @throws \Exception
     */
    public static function create(
        string $name,
        CategoryTask $categoryTask,
        User $owner,
        bool $displayInGroup,
        ?\DateTime $startAt,
        ?\DateTime $dueAt,
        ?User $personAffected
    ): Task {
        return new Task(
            $name,
            $categoryTask,
            $owner,
            $displayInGroup,
            $startAt,
            $dueAt,
            $personAffected
        );
    }
}
