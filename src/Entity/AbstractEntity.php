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

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class AbstractEntity.
 */
abstract class AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Groups({"all"})
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"all"})
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"all"})
     */
    protected $updatedAt;

    /**
     * AbstractEntity constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
