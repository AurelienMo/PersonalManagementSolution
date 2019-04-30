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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /** @var EntityManagerInterface */
    protected $entityManager;

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
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') &&
            !is_null($input->getAffectTo())
        ) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous ne pouvez pas affecter une tâche à un admin ou créateur du groupe.'
            );
        }

        return $input;
    }

    protected function getInputClass(): string
    {
        return AddTaskInput::class;
    }
}
