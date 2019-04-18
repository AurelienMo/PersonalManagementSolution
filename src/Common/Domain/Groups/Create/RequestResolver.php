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

namespace App\Common\Domain\Groups\Create;

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
     * @return NewGroupInput
     *
     * @throws ValidatorException
     */
    public function resolve(Request $request)
    {
        if (!$this->authorizationChecker->isGranted('ALREADY_HAVE_GROUP', $this->getCurrentUser())) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous ne pouvez pas créer de groupe car vous appartenez déjà à un groupe.'
            );
        }

        /** @var NewGroupInput $input */
        $input = $this->hydrateInputFromPayload($request);
        /** @var User $currentUser */
        $currentUser = $this->getCurrentUser();
        $input->setOwner($currentUser);
        $this->validate($input);

        return $input;
    }

    protected function getInputClass(): string
    {
        return NewGroupInput::class;
    }
}
