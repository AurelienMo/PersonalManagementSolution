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
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Loader
 */
class Loader
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var RegistryInterface */
    protected $registry;

    /**
     * Loader constructor.
     *
     * @param SerializerInterface $serializer
     * @param RegistryInterface   $registry
     */
    public function __construct(
        SerializerInterface $serializer,
        RegistryInterface $registry
    ) {
        $this->serializer = $serializer;
        $this->registry = $registry;
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
        $group = $this->registry->getManager('common')->getRepository(Group::class)
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
