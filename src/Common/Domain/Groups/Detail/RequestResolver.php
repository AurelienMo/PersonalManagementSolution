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
use App\Globals\Domain\AbstractRequestResolver;
use App\Globals\Domain\Common\Helpers\RequestExtractorParams;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
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
    /** @var RegistryInterface */
    protected $registry;

    /**
     * RequestResolver constructor.
     *
     * @param SerializerInterface           $serializer
     * @param ValidatorInterface            $validator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     * @param RegistryInterface             $registry
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        RegistryInterface $registry
    ) {
        $this->registry = $registry;
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
     * @return DetailGroupInput|mixed
     *
     * @throws NonUniqueResultException
     * @throws \ReflectionException
     */
    public function resolve(Request $request)
    {
        $groupId = RequestExtractorParams::extract($request, RequestExtractorParams::PATH, 'id');
        $group = $this->registry->getManager('common')->getRepository(Group::class)
            ->getGroupById($groupId);

        if (\is_null($group)) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Ce groupe n\'existe pas.'
            );
        }

        if (!$this->authorizationChecker->isGranted('USER_IN_GROUP', $groupId)) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Vous n\'êtes pas autorisé à accéder aux informations de ce groupe.'
            );
        }

        /** @var DetailGroupInput $input */
        $input = $this->instanciateInputClass();
        $input->setGroup($group);

        return $input;
    }

    /**
     * @return string
     */
    protected function getInputClass(): string
    {
        return DetailGroupInput::class;
    }
}
