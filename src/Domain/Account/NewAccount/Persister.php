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

use App\Domain\AbstractPersister;
use App\Domain\Common\Events\MailEvent;
use App\Domain\Common\Helpers\TokenGenerator;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /**
     * Persister constructor.
     *
     * @param SerializerInterface      $serializer
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     * @param EncoderFactoryInterface  $encoderFactory
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->encoderFactory = $encoderFactory;
        parent::__construct(
            $serializer,
            $entityManager,
            $eventDispatcher,
            $logger
        );
    }

    /**
     * @param NewAccountInput $input
     *
     * @throws \Exception
     */
    public function save(NewAccountInput $input)
    {
        $user = UserFactory::build(
            $input->getFirstname(),
            $input->getLastname(),
            $input->getUsername(),
            $this->encoderFactory->getEncoder(User::class)->encodePassword($input->getPassword(), ''),
            $input->getEmail(),
            TokenGenerator::generate()
        );

        try {
            $this->persistSave($user);
        } catch (ORMException $e) {
            $this->logger->critical($e->getMessage());
            throw new HttpException(
                500,
                'Une erreur à la création du compte a été rencontré. Merci de réessayer plus tard.'
            );
        }

        $this->eventDispatcher->dispatch(
            MailEvent::ON_REGISTRATION,
            new MailEvent(null, $user)
        );
    }
}
