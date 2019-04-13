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

namespace App\Common\Domain\Account\NewAccount;

use App\Common\Entity\User;
use App\Globals\Domain\AbstractRequestResolver;
use App\Globals\Domain\Common\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /**
     * @param Request $request
     *
     * @return mixed|object
     *
     * @throws ValidatorException
     */
    public function resolve(Request $request)
    {
        if (!\is_null($this->tokenStorage->getToken()) && $this->tokenStorage->getToken()->getUser() instanceof User) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous ne pouvez pas vous inscrire en étant connecté.'
            );
        }

        /** @var NewAccountInput $input */
        $input = $this->hydrateInputFromPayload($request);
        $this->validate($input);

        return $input;
    }

    /**
     * @return string
     */
    protected function getInputClass(): string
    {
        return NewAccountInput::class;
    }
}
