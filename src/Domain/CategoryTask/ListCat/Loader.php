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

namespace App\Domain\CategoryTask\ListCat;

use App\Entity\CategoryTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Loader
 */
class Loader
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * Loader constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface    $serializer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function load(ListCatInput $input): ?string
    {
        $categories = $this->entityManager->getRepository(CategoryTask::class)
                                          ->loadCat($input->getKeywords());

        if (empty($categories)) {
            return null;
        }

        return $this->serializer->serialize(
            $categories,
            'json',
            [
                'groups' => ['all']
            ]
        );
    }
}
