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

namespace App\Domain\Groups\Detail;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Loader.
 */
class Loader
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * Loader constructor.
     *
     * @param SerializerInterface    $serializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ) {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param DetailGroupInput $input
     *
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function load(DetailGroupInput $input)
    {
        $group = $this->entityManager->getRepository(Group::class)
                                     ->loadFullInformations($input->getGroup()->getId());

        return $this->serializer->serialize(
            $group,
            'json',
            [
                'groups' => [
                    'all',
                    'group_detail_show',
                ],
            ]
        );
    }
}
