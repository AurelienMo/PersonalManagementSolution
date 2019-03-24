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

namespace App\Domain;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\Common\Factory\ValidatorErrorsFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractRequestResolver
 */
abstract class AbstractRequestResolver
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /**
     * AbstractRequestResolver constructor.
     *
     * @param SerializerInterface           $serializer
     * @param ValidatorInterface            $validator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function resolve(Request $request);

    /**
     * @return string
     */
    abstract protected function getInputClass(): string;

    /**
     * @param Request $request
     *
     * @return object
     */
    protected function hydrateInputFromPayload(Request $request)
    {
        return $this->serializer->deserialize(
            $request->getContent(),
            $this->getInputClass(),
            'json'
        );
    }

    /**
     * @return InputInterface
     *
     * @throws \ReflectionException
     */
    protected function instanciateInputClass()
    {
        $reflectClass = new \ReflectionClass($this->getInputClass());
        $className = $reflectClass->name;

        return $className();
    }

    /**
     * @param InputInterface $input
     *
     * @throws ValidatorException
     */
    protected function validate(InputInterface $input)
    {
        $constraintList = $this->validator->validate($input);
        if ($constraintList->count() > 0) {
            throw new ValidatorException(
                ValidatorErrorsFactory::build($constraintList)
            );
        }
    }
}
