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

namespace App\Common\Actions\API\Groups;

use App\Common\Domain\Groups\Create\Persister;
use App\Common\Domain\Groups\Create\RequestResolver;
use App\Globals\Domain\Common\Exceptions\ValidatorException;
use App\Globals\Responders\JsonResponder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateGroup
 *
 * @Route("/groups", name="create_group", methods={"POST"})
 */
class CreateGroup
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * CreateGroup constructor.
     *
     * @param RequestResolver $requestResolver
     * @param Persister       $persister
     */
    public function __construct(
        RequestResolver $requestResolver,
        Persister $persister
    ) {
        $this->requestResolver = $requestResolver;
        $this->persister = $persister;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidatorException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $output = $this->persister->save($input);

        return JsonResponder::response(
            $request,
            $output,
            201
        );
    }
}
