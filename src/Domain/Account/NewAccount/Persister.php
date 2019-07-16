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

namespace App\Domain\Account\NewAccount;

use App\Domain\Common\Factory\UserFactory;
use App\Entity\User;
use App\Domain\AbstractPersister;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Persister.
 */
class Persister extends AbstractPersister
{
    /** @var EncoderFactoryInterface */
    protected $encoder;

    /** @var UserRepository */
    protected $userRepo;

    /**
     * Persister constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerInterface      $serializer
     * @param EncoderFactoryInterface  $encoder
     * @param UserRepository           $userRepo
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer,
        EncoderFactoryInterface $encoder,
        UserRepository $userRepo
    ) {
        $this->encoder = $encoder;
        $this->userRepo = $userRepo;
        parent::__construct(
            $eventDispatcher,
            $serializer
        );
    }

    /**
     * @param NewAccountInput $input
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function save(NewAccountInput $input)
    {
        $encoder = $this->encoder->getEncoder(User::class);
        $user = UserFactory::create(
            $input->getFirstname(),
            $input->getLastname(),
            $input->getUsername(),
            $encoder->encodePassword($input->getPassword(), ''),
            $input->getEmail()
        );
        $this->userRepo->persistSave($user, true);

        $this->eventDispatcher->dispatch(
            MailSubscriptionEvent::USER_SUBSCRIPTION_MAIL,
            new MailSubscriptionEvent($user)
        );
    }
}
