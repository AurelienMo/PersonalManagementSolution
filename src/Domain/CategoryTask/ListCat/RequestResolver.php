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

namespace App\Domain\CategoryTask\ListCat;

use App\Domain\AbstractRequestResolver;
use App\Domain\Common\Helpers\RequestExtractorParams;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /**
     * @param Request $request
     *
     * @return ListCatInput
     *
     * @throws \ReflectionException
     */
    public function resolve(Request $request)
    {
        /** @var ListCatInput $input */
        $input = $this->instanciateInputClass();
        if ($request->query->has('search')) {
            $input->setKeywords(
                RequestExtractorParams::extract($request, RequestExtractorParams::QUERY, 'search')
            );
        }

        return $input;
    }

    protected function getInputClass(): string
    {
        return ListCatInput::class;
    }
}
