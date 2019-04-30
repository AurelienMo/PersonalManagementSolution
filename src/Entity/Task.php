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

use App\Entity\AdditionnalInformationsEntities\TaskStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Task
 *
 * @ORM\Table(name="amo_task")
 * @ORM\Entity()
 */
class Task extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"detail_task"})
     */
    protected $name;

    /**
     * @var CategoryTask
     *
     * @ORM\ManyToOne(targetEntity="CategoryTask")
     * @ORM\JoinColumn(name="amo_category_task_id", referencedColumnName="id")
     *
     * @Groups({"detail_task"})
     */
    protected $category;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups({"detail_task"})
     */
    protected $displayInGroup;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"detail_task"})
     */
    protected $startAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"detail_task"})
     */
    protected $dueAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"detail_task"})
     */
    protected $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownerTasks")
     * @ORM\JoinColumn(name="amo_owner_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Groups({"detail_task"})
     */
    protected $owner;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="affectedTasks")
     * @ORM\JoinColumn(name="amo_person_affected_id", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     *
     * @Groups({"detail_task"})
     */
    protected $personAffected;

    /**
     * Task constructor.
     *
     * @param string         $name
     * @param CategoryTask   $category
     * @param User           $owner
     * @param bool           $displayInGroup
     * @param string         $status
     * @param \DateTime|null $startAt
     * @param \DateTime|null $dueAt
     * @param User|null      $personAffected
     *
     * @throws \Exception
     */
    public function __construct(
        string $name,
        CategoryTask $category,
        User $owner,
        bool $displayInGroup = false,
        \DateTime $startAt = null,
        \DateTime $dueAt = null,
        ?User $personAffected = null,
        string $status = TaskStatus::TODO
    ) {
        $this->name = $name;
        $this->category = $category;
        $this->status = $status;
        $this->owner = $owner;
        $this->displayInGroup = $displayInGroup;
        $this->startAt = $startAt;
        $this->dueAt = $dueAt;
        $this->personAffected = $personAffected;
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
     * @return CategoryTask
     */
    public function getCategory(): CategoryTask
    {
        return $this->category;
    }

    /**
     * @return bool
     */
    public function isDisplayInGroup(): bool
    {
        return $this->displayInGroup;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getDueAt(): ?\DateTime
    {
        return $this->dueAt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return User|null
     */
    public function getPersonAffected(): ?User
    {
        return $this->personAffected;
    }
}
