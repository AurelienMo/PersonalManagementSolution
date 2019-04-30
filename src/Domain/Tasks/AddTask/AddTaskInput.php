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

namespace App\Domain\Tasks\AddTask;

use App\Domain\InputInterface;
use App\Domain\Tasks\Constraints\DueDateLessThan;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddTaskInput
 *
 * @DueDateLessThan(
 *     message="La date d'échéance ne peut pas être inférieur à la date de début."
 * )
 */
class AddTaskInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Le nom de la tâche est requis."
     * )
     */
    protected $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     message="Une catégorie de tâche est requise."
     * )
     */
    protected $category;

    /**
     * @var \DateTime|null
     */
    protected $startDate;

    /**
     * @var \DateTime|null
     */
    protected $dueDate;

    /**
     * @var bool|null
     */
    protected $displayInGroup;

    /**
     * @var string|null
     */
    protected $affectTo;

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

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $startDate
     */
    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime|null $dueDate
     */
    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return bool|null
     */
    public function getDisplayInGroup(): ?bool
    {
        return $this->displayInGroup;
    }

    /**
     * @param bool|null $displayInGroup
     */
    public function setDisplayInGroup(?bool $displayInGroup): void
    {
        $this->displayInGroup = $displayInGroup;
    }

    /**
     * @return string|null
     */
    public function getAffectTo(): ?string
    {
        return $this->affectTo;
    }

    /**
     * @param string|null $affectTo
     */
    public function setAffectTo(?string $affectTo): void
    {
        $this->affectTo = $affectTo;
    }
}
