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

namespace App\Domain\Tasks\AddTask;

use App\Domain\AbstractPersister;
use App\Domain\Common\Factory\CategoryTaskFactory;
use App\Domain\Common\Factory\TaskFactory;
use App\Entity\CategoryTask;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\CategoryTaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /**
     * Persister constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface      $serializer
     * @param TokenStorageInterface    $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
        parent::__construct(
            $entityManager,
            $eventDispatcher,
            $serializer
        );
    }

    /**
     * @param AddTaskInput $input
     *
     * @return string|null
     *
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function save(AddTaskInput $input): ?string
    {
        /** @var User $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();
        $task = TaskFactory::create(
            $input->getName(),
            $this->getCategoryTask($input->getCategory()),
            $currentUser,
            $input->getDisplayInGroup() ?? false,
            $input->getStartDate() ?? null,
            $input->getDueDate() ?? null,
            $this->getAffected($currentUser, $input->getAffectTo(), $input->getDisplayInGroup() ?? false)
        );

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->serializer->serialize(
            $task,
            'json',
            [
                'groups' => ['all', 'detail_task']
            ]
        );
    }

    /**
     * @param string|null $name
     *
     * @return CategoryTask|null
     *
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    private function getCategoryTask(?string $name)
    {
        /** @var CategoryTaskRepository $repo */
        $repo = $this->entityManager->getRepository(CategoryTask::class);
        $category = $repo->loadByName($name);

        if (\is_null($category)) {
            $category = CategoryTaskFactory::create($name);
            $this->entityManager->persist($category);
        }

        return $category;
    }

    /**
     * @param User        $currentUser
     * @param string|null $getAffectTo
     * @param bool        $displayInGroup
     *
     * @return User|mixed|null
     *
     * @throws NonUniqueResultException
     */
    private function getAffected(User $currentUser, ?string $getAffectTo, bool $displayInGroup)
    {
        if ($displayInGroup && !\is_null($getAffectTo)) {
            /** @var UserRepository $repo */
            $repo = $this->entityManager->getRepository(User::class);

            return $repo->loadByFullNameInGroup($currentUser->getGroup(), $getAffectTo);
        } else if (\is_null($getAffectTo) && !$displayInGroup) {
            return $currentUser;
        }

        return null;
    }
}
