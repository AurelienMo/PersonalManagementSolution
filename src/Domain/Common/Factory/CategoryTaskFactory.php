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

/**
 * Class CategoryTaskFactory
 */
class CategoryTaskFactory
{
    /**
     * @param string $name
     *
     * @return CategoryTask
     *
     * @throws \Exception
     */
    public static function create(
        string $name
    ): CategoryTask {
        return new CategoryTask($name);
    }
}
