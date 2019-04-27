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

namespace App\Domain\Groups\RemoveMember;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class UserAuthInGroupVoter
 */
class UserAuthInGroupVoter extends Voter
{
    const ATTRIBUTE = 'USER_AUTH_IS_IN_GROUP';

    protected function supports(
        $attribute,
        $subject
    ) {
        return self::ATTRIBUTE === $attribute;
    }

    protected function voteOnAttribute(
        $attribute,
        $subject,
        TokenInterface $token
    ) {
        if (!$token->getUser() instanceof User || \is_null($token->getUser()->getGroup())) {
            return false;
        }

        return $token->getUser()->getGroup()->getId() === $subject;
    }
}
