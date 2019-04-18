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

namespace App\Common\Domain\Groups\Detail;

use App\Common\Entity\Group;
use App\Common\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class DetailGroupVoter
 */
class DetailGroupVoter extends Voter
{
    const USER_IN_GROUP = 'USER_IN_GROUP';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports(
        $attribute,
        $subject
    ) {
        if ($attribute !== self::USER_IN_GROUP) {
            return false;
        }

        return true;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
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
