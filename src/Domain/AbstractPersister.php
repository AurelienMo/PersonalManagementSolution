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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractPersister.
 */
abstract class AbstractPersister
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * AbstractPersister constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }
}
