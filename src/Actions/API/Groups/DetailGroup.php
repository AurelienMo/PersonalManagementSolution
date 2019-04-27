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

use App\Domain\Groups\Detail\Loader;
use App\Domain\Groups\Detail\RequestResolver;
use App\Responders\JsonResponder;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DetailGroup.
 *
 * @Route("/groups/{id}", name="get_detail_group", methods={"GET"})
 */
class DetailGroup
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Loader */
    protected $loader;

    /**
     * DetailGroup constructor.
     *
     * @param RequestResolver $requestResolver
     * @param Loader          $loader
     */
    public function __construct(
        RequestResolver $requestResolver,
        Loader $loader
    ) {
        $this->requestResolver = $requestResolver;
        $this->loader = $loader;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws \ReflectionException
     */
    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $output = $this->loader->load($input);

        return JsonResponder::response(
            $request,
            $output
        );
    }
}
