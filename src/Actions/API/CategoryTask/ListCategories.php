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

use App\Domain\CategoryTask\ListCat\Loader;
use App\Domain\CategoryTask\ListCat\RequestResolver;
use App\Responders\JsonResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListCategories
 *
 * @Route("/taskCategories", name="list_task_categories", methods={"GET"})
 */
class ListCategories
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Loader */
    protected $loader;

    /**
     * ListCategories constructor.
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

    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $output = $this->loader->load($input);

        return JsonResponder::response(
            $request,
            $output,
            \is_null($output) ? 204 : 200
        );
    }
}
