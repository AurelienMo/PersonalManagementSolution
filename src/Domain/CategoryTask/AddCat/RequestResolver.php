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

namespace App\Domain\CategoryTask\AddCat;

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
     * @return AddCatInput|mixed
     *
     * @throws ValidatorException
     */
    public function resolve(Request $request)
    {
        /** @var AddCatInput $input */
        $input = $this->hydrateInputFromPayload($request);
        $this->validate($input);

        return $input;
    }

    protected function getInputClass(): string
    {
        return AddCatInput::class;
    }
}
