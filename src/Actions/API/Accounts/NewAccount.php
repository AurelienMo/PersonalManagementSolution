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

namespace App\Actions\API\Accounts;

use App\Actions\API\AbstractAction;
use App\Domain\Account\NewAccount\RequestResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewAccount
 *
 * @Route("/accounts", name="create_account", methods={"POST"})
 */
class NewAccount extends AbstractAction
{
    /** @var RequestResolver */
    protected $requestResolver;

    /**
     * NewAccount constructor.
     *
     * @param RequestResolver $requestResolver
     */
    public function __construct(
        RequestResolver $requestResolver
    ) {
        $this->requestResolver = $requestResolver;
    }

    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);

        return $this->sendResponse(
            null,
            201
        );
    }
}
