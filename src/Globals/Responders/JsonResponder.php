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

namespace App\Globals\Responders;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponder
 */
class JsonResponder
{
    /**
     * @param string|null $datas
     * @param int         $statusCode
     * @param bool        $isCacheable
     * @param array       $additionalHeaders
     *
     * @return Response
     */
    public static function response(
        ?string $datas = null,
        int $statusCode = 200,
        bool $isCacheable = false,
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

        if ($isCacheable) {
            $response->setPublic()
                     ->setSharedMaxAge(3600);
        }

        return $response;
    }
}
