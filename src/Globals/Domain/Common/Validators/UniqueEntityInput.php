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

namespace App\Globals\Domain\Common\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Class UniqueEntityInput
 *
 * @Annotation
 */
class UniqueEntityInput extends Constraint
{
    public $message = 'This value should be unique.';

    /** @var string */
    public $class;

    /** @var array */
    public $fields = [];

    public function __construct(
        $options = null
    ) {
        if (!is_null($options) && !\is_array($options)) {
            $options = [
                'class' => $options
            ];
        }
        parent::__construct($options);
        if (!$this->class) {
            throw new MissingOptionsException(
                sprintf("Either option 'class' must be define for constraint %s", __CLASS__),
                ['class']
            );
        }
    }

    public function getRequiredOptions()
    {
        return [
            'fields',
            'class',
        ];
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
