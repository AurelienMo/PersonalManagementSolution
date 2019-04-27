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

namespace App\Entity;

use App\Domain\Common\Helpers\Slugger;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Group.
 *
 * @ORM\Table(name="amo_group")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"group_detail_show"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"group_detail_show"})
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $passwordToJoin;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="amo_owner_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     *
     * @Groups({"group_detail_show"})
     */
    protected $owner;

    /**
     * @var User[]|Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     *
     * @Groups({"group_detail_show"})
     */
    protected $members;

    /**
     * Group constructor.
     *
     * @param string $name
     * @param string $passwordToJoin
     * @param User   $owner
     *
     * @throws \Exception
     */
    public function __construct(
        string $name,
        string $passwordToJoin,
        User $owner
    ) {
        $this->name = $name;
        $this->passwordToJoin = $passwordToJoin;
        $this->slug = Slugger::slugify($name);
        $this->owner = $owner;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getPasswordToJoin(): string
    {
        return $this->passwordToJoin;
    }

    /**
     * @return User[]|Collection
     */
    public function getMembers()
    {
        return $this->members;
    }
}
