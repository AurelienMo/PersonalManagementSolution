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

namespace App\Domain\CategoryTask\AddCat;

use App\Domain\Common\Validators\UniqueEntityInput;
use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddCatInput
 *
 * @UniqueEntityInput(
 *     class="App\Entity\CategoryTask",
 *     fields={"name"},
 *     message="Ce nom de catégorie existe déjà."
 * )
 */
class AddCatInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Un nom est requis pour ajouter une catégorie."
     * )
     */
    protected $name;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
