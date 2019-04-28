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

namespace App\Domain\Groups\PromoteMember;

use App\Domain\InputInterface;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PromoteMemberInput
 */
class PromoteMemberInput implements InputInterface
{
    /** @var Group */
    protected $group;

    /** @var User */
    protected $member;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le nouveau rôle est obligatoire."
     * )
     */
    protected $role;

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

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
