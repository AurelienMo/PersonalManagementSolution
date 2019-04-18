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

namespace App\Common\Domain\Common\Factory;

use App\Common\Entity\Group;
use App\Common\Entity\User;

/**
 * Class GroupFactory
 */
class GroupFactory
{
    /**
     * @param string $name
     * @param string $password
     * @param User   $owner
     *
     * @return Group
     *
     * @throws \Exception
     */
    public static function create(
        string $name,
        string $password,
        User $owner
    ) {
        return new Group($name, $password, $owner);
    }
}
