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

namespace App\Domain\Common\Exceptions;

/**
 * Class ValidatorException.
 */
class ValidatorException extends \Exception
{
    /** @var array */
    protected $errors;

    /**
     * ValidatorException constructor.
     *
     * @param array $errors
     */
    public function __construct(
        array $errors
    ) {
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
