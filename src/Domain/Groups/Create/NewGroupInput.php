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

namespace App\Domain\Groups\Create;

use App\Entity\User;
use App\Domain\Common\Validators\UniqueEntityInput;
use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NewGroupInput.
 *
 * @UniqueEntityInput(
 *     class="App\Entity\Group",
 *     fields={"name"},
 *     message="Ce groupe existe déjà."
 * )
 */
class NewGroupInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le nom du groupe est obligatoire."
     * )
     */
    protected $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le mot de passe permettant de rejoindre le groupe est obligatoire."
     * )
     */
    protected $passwordToJoin;

    /** @var User */
    protected $owner;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getPasswordToJoin(): ?string
    {
        return $this->passwordToJoin;
    }

    /**
     * @param string|null $passwordToJoin
     */
    public function setPasswordToJoin(?string $passwordToJoin): void
    {
        $this->passwordToJoin = $passwordToJoin;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }
}
