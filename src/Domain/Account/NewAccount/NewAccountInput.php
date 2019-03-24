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

namespace App\Domain\Account\NewAccount;

use App\Domain\Common\Validators\UniqueEntityInput;
use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NewAccountInput
 *
 * @UniqueEntityInput(
 *     class="App\Entity\User",
 *     fields={"username", "email"},
 *     message="Utilisateur déjà existant."
 * )
 */
class NewAccountInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le prénom est requis."
     * )
     */
    protected $firstname;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le nom est requis."
     * )
     */
    protected $lastname;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="L'adresse email est requise."
     * )
     */
    protected $email;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le nom d'utilisateur est requis."
     * )
     */
    protected $username;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le mot de passe est requis."
     * )
     * @Assert\Length(
     *     min="8",
     *     minMessage="Votre mot de passe doit contenir 8 caractères."
     * )
     */
    protected $password;

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
