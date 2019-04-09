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

namespace App\Globals\Domain\Common\Helpers;

/**
 * Class TokenGenerator
 */
class TokenGenerator
{
    /**
     * @return string
     */
    public static function generate()
    {
        return md5(uniqid());
    }
}
