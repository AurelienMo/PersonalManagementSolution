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

namespace App\Actions\API\Groups;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\Groups\PromoteMember\Persister;
use App\Domain\Groups\PromoteMember\RequestResolver;
use App\Responders\JsonResponder;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PromoteMember
 *
 * @Route("/groups/{id}/members/{memberId}", name="promote_member", methods={"PUT"})
 */
class PromoteMember
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * PromoteMember constructor.
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
     * @throws NonUniqueResultException
     */
    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $this->persister->save($input);

        return JsonResponder::response(
            $request,
            null,
            200
        );
    }
}
