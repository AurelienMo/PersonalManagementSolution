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

namespace App\Globals\Domain;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractPersister
 */
abstract class AbstractPersister
{
    /** @var RegistryInterface */
    protected $registry;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * AbstractPersister constructor.
     *
     * @param RegistryInterface        $registry
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        RegistryInterface $registry,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->registry = $registry;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    /**
     * @param string $manager
     *
     * @return ObjectManager
     */
    protected function getManager(string $manager)
    {
        return $this->registry->getManager($manager);
    }
}
