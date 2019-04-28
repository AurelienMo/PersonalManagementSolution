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

use App\Domain\AbstractRequestResolver;
use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\Common\Helpers\RequestExtractorParams;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        parent::__construct(
            $serializer,
            $validator,
            $authorizationChecker,
            $tokenStorage
        );
    }

    /**
     * @param Request $request
     *
     * @return PromoteMemberInput|mixed
     *
     * @throws NonUniqueResultException
     * @throws ValidatorException
     */
    public function resolve(Request $request)
    {
        /** @var PromoteMemberInput $input */
        $input = $this->hydrateInputFromPayload($request);

        $group = $this->getGroup($request);
        if (\is_null($group)) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Ce groupe n\'existe pas.'
            );
        }

        $member = $this->getMember($group, $request);
        if (!$member) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Ce membre n\'existe pas.'
            );
        }
        if (!$this->authorizationChecker->isGranted('USER_AUTH_IS_IN_GROUP', $group->getId())) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous n\'êtes pas autorisé à modifier le statut d\'un membre d\'un groupe dont'.
                ' vous n\'êtes pas le propriétaire.'
            );
        }
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous n\'avez pas les permissions suffisantes pour modifier le statut du membre.'
            );
        }
        if (!$this->authorizationChecker->isGranted('UPDATE_OWNER_WITH_NO_OWNER', $member)) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous ne pouvez pas modifier le rôle du propriétaire du groupe.'
            );
        }
        $this->validate($input);

        return $input;
    }

    protected function getInputClass(): string
    {
        return PromoteMemberInput::class;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    private function getGroup(Request $request)
    {
        $groupId = RequestExtractorParams::extract(
            $request,
            RequestExtractorParams::PATH,
            'id'
        );
        /** @var GroupRepository $repo */
        $repo = $this->entityManager->getRepository(Group::class);

        return $repo->getGroupById($groupId);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return bool|mixed
     */
    private function getMember(Group $group, Request $request)
    {
        $memberId = RequestExtractorParams::extract(
            $request,
            RequestExtractorParams::PATH,
            'memberId'
        );

        $result = $group->getMembers()->filter(function (User $member) use ($memberId) {
            return $memberId === $member->getId();
        });

        return $result->count() > 0 ? $result->first() : false;
    }
}
