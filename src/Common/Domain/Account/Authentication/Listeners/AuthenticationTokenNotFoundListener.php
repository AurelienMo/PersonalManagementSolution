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

namespace App\Common\Domain\Account\Authentication\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AuthenticationTokenNotFoundListener
 */
class AuthenticationTokenNotFoundListener
{
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $response = new JsonResponse(
            [
                'code' => 401,
                'message' => 'Jeton d\'identification non trouvé.'
            ]
        );

        $event->setResponse($response);
    }
}
