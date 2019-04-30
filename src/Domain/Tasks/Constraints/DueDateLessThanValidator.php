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

use App\Domain\Tasks\AddTask\AddTaskInput;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DueDateLessThanValidator
 */
class DueDateLessThanValidator extends ConstraintValidator
{
    public function validate(
        $value,
        Constraint $constraint
    ) {
        /** @var AddTaskInput $object */
        $object = $value;
        if (!is_null($object->getStartDate()) && !is_null($object->getDueDate())) {
            if ($object->getDueDate() < $object->getStartDate()) {
                $this->context->buildViolation($constraint->message)
                              ->addViolation();
            }
        }
    }
}
