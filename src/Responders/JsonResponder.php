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

namespace App\Responders;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponder.
 */
class JsonResponder
{
    /**
     * @param Request     $request
     * @param string|null $datas
     * @param int         $statusCode
     * @param array       $additionalHeaders
     *
     * @return Response
     */
    public static function response(
        ?Request $request,
        ?string $datas = null,
        int $statusCode = 200,
        array $additionalHeaders = []
    ): Response {
        $response = new Response(
            $datas,
            $statusCode,
            array_merge(
                [
                    'Content-Type' => 'application/json',
                ],
                $additionalHeaders
            )
        );

        if (!\is_null($request) && $request->isMethodCacheable()) {
            $response->setPublic()
                     ->setSharedMaxAge(3600);
        }

        return $response;
    }
}
