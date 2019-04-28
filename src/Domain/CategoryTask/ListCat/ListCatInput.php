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

namespace App\Domain\CategoryTask\ListCat;

/**
 * Class ListCatInput
 */
class ListCatInput
{
    /** @var string|null */
    protected $keywords;

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string|null $keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }
}
