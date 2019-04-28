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

namespace App\Domain\Groups\RemoveMember;

use App\Entity\Group;
use App\Entity\User;

/**
 * Class RemoveMemberInput
 */
class RemoveMemberInput
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var User
     */
    protected $member;

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    /**
     * @return User
     */
    public function getMember(): User
    {
        return $this->member;
    }

    /**
     * @param User $member
     */
    public function setMember(User $member): void
    {
        $this->member = $member;
    }
}