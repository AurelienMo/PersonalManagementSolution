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

namespace App\Globals\Domain\Common\Factory;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidatorErrorsFactory
 */
class ValidatorErrorsFactory
{
    /**
     * @param ConstraintViolationListInterface $violationList
     *
     * @return array
     */
    public static function build(ConstraintViolationListInterface $violationList)
    {
        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
