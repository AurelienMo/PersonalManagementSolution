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

namespace App\Domain\Tasks\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class DueDateLessThan
 *
 * @Annotation
 */
class DueDateLessThan extends Constraint
{
    public $message = 'Invalid value';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
