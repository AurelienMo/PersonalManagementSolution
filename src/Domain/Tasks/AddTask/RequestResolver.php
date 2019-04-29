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

use App\Domain\AbstractRequestResolver;
use App\Domain\Common\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /**
     * @param Request $request
     *
     * @return AddTaskInput
     *
     * @throws ValidatorException
     */
    public function resolve(Request $request): AddTaskInput
    {
        /** @var AddTaskInput $input */
        $input = $this->hydrateInputFromPayload($request);
        $this->validate($input);

        return $input;
    }

    protected function getInputClass(): string
    {
        return AddTaskInput::class;
    }
}
