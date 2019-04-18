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

namespace App\Common\Domain\Groups\Create;

use App\Common\Entity\Group;
use App\Common\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class AlreadyHaveGroupVoter
 */
class AlreadyHaveGroupVoter extends Voter
{
    const ATTRIBUTE = 'ALREADY_HAVE_GROUP';

    protected function supports(
        $attribute,
        $subject
    ) {
        return $attribute === self::ATTRIBUTE && $subject instanceof User;
    }

    /**
     * @param string         $attribute
     * @param User          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(
        $attribute,
        $subject,
        TokenInterface $token
    ) {
        if ($subject->getGroup() instanceof Group) {
            return false;
        }

        return true;
    }
}
