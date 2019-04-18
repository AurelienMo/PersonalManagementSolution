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

namespace App\Globals\Domain\Common\Helpers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestExtractorParams
 */
class RequestExtractorParams
{
    const QUERY = 'query';
    const PATH = 'path';

    /**
     * @param Request $request
     * @param string  $type
     * @param string  $value
     *
     * @return mixed
     */
    public static function extract(
        Request $request,
        string $type,
        string $value
    ) {
        return $type === self::QUERY ?
            $request->query->get($value) :
            $request->attributes->get($value);
    }
}
