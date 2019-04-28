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

namespace App\Domain\Groups\PromoteMember;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class UpdateOwnerForbiddenVoter
 */
class UpdateOwnerForbiddenVoter extends Voter
{
    /** @var Security */
    protected $security;


    const ATTRIBUTE = 'UPDATE_OWNER_WITH_NO_OWNER';

    /**
     * UpdateOwnerForbiddenVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

    protected function supports(
        $attribute,
        $subject
    ) {
        return $attribute === self::ATTRIBUTE;
    }

    protected function voteOnAttribute(
        $attribute,
        $subject,
        TokenInterface $token
    ) {
        if ($this->security->isGranted('ROLE_OWNER', $subject) &&
            !$this->security->isGranted('ROLE_OWNER', $token->getUser())
        ) {
            return false;
        }

        return true;
    }
}
