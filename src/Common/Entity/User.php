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

namespace App\Common\Entity;

use App\Common\Entity\AdditionnalInformationsEntities\UserStatus;
use App\Globals\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class User
 *
 * @ORM\Table(name="amo_user")
 * @ORM\Entity(repositoryClass="App\Common\Repository\UserRepository")
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

    public function updateRole(string $role)
    {
        $this->roles[] = $role;
    }
}
