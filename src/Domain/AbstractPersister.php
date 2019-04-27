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

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractPersister.
 */
abstract class AbstractPersister
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * AbstractPersister constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    /**
     * @param string $className
     *
     * @return ObjectRepository
     */
    protected function getRepository(string $className)
    {
        return $this->entityManager->getRepository($className);
    }
}
