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

use App\Entity\AdditionnalInformationsEntities\UserStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class User.
 *
 * @ORM\Table(name="amo_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"group_detail_show"})
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"group_detail_show"})
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var Group|null
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="members")
     * @ORM\JoinColumn(name="amo_group_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $group;

    /**
     * @var Task[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="owner", cascade={"remove", "persist"})
     */
    protected $ownerTasks;

    /**
     * @var Task[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="personAffected", cascade={"remove", "persist"})
     */
    protected $affectedTasks;

    /**
     * User constructor.
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $status
     *
     * @throws \Exception
     */
    public function __construct(
        string $firstname,
        string $lastname,
        string $username,
        string $password,
        string $email,
        string $status = UserStatus::STATUS_ENABLED
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles[] = 'ROLE_USER';
        $this->status = $status;
        $this->ownerTasks = new ArrayCollection();
        $this->affectedTasks = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function defineGroup(?Group $group)
    {
        $this->group = $group;
    }

    /**
     * @param string $role
     * @param bool   $remove
     */
    public function updateRole(string $role, bool $remove = false)
    {
        if ($remove) {
            unset($this->roles[$role]);
        } else {
            $this->roles[] = $role;
        }
    }

    /**
     * @return Task[]|Collection
     */
    public function getOwnerTasks()
    {
        return $this->ownerTasks;
    }

    /**
     * @return Task[]|Collection
     */
    public function getAffectedTasks()
    {
        return $this->affectedTasks;
    }

    /**
     * @param Task $task
     *
     * @return $this
     */
    public function addNewRespTask(Task $task)
    {
        $this->ownerTasks->add($task);

        return $this;
    }

    public function removeNewRespTask(Task $task)
    {
        $this->ownerTasks->removeElement($task);
    }

    public function addDoingTask(Task $task)
    {
        $this->affectedTasks->add($task);

        return $this;
    }

    /**
     * @param Task $task
     */
    public function removeDoingTask(Task $task)
    {
        $this->affectedTasks->add($task);
    }
}
