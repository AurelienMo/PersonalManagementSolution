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

/**
 * Class CategoryTask
 *
 * @ORM\Table(name="amo_category_task")
 * @ORM\Entity()
 */
class CategoryTask extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * CategoryTask constructor.
     *
     * @param string $name
     *
     * @throws \Exception
     */
    public function __construct(
        string $name
    ) {
        $this->name = $name;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
