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

namespace App\Actions\API\CategoryTask;

use App\Domain\CategoryTask\AddCat\Persister;
use App\Domain\CategoryTask\AddCat\RequestResolver;
use App\Responders\JsonResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddCategory
 *
 * @Route("/taskCategories", name="add_category", methods={"POST"})
 */
class AddCategory
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * AddCategory constructor.
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


    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $this->persister->save($input);

        return JsonResponder::response(
            $request,
            null,
            201
        );
    }
}
