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

namespace App\Globals\Actions\API;

use App\Globals\Responders\JsonResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractAction
 */
abstract class AbstractAction
{
    public function sendResponse(
        ?string $datas = null,
        int $statusCode = Response::HTTP_OK,
        bool $isCacheable = false,
        array $additionalHeaders = []
    ): Response {
        return JsonResponder::response(
            $datas,
            $statusCode,
            $isCacheable,
            $additionalHeaders
        );
    }
}
