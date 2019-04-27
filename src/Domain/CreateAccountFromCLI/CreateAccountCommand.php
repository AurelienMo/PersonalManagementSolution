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

namespace App\Domain\CreateAccountFromCLI;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class CreateAccountCommand.
 */
class CreateAccountCommand extends Command
{
    const LIST_FIELDS_TO_CREATE = [
        'firstname' => null,
        'lastname' => null,
        'username' => null,
        'password' => null,
        'email' => null,
    ];

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EncoderFactoryInterface */
    protected $encoder;

    /**
     * CreateAccountCommand constructor.
     *
     * @param EntityManagerInterface  $entityManager
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->entityManager = $entityManager;
        $this->encoder = $encoderFactory;
        parent::__construct();
    }

    /**
     * Configure command to create account.
     */
    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Allow to create user account from cli.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $fields = self::LIST_FIELDS_TO_CREATE;
        foreach ($fields as $field => $value) {
            $question = new Question(
                sprintf("Define value for '%s' field : ", $field)
            );
            $fields[$field] = $this->getQuestionHelper()->ask($input, $output, $question);
        }

        $user = new User(
            $fields['firstname'],
            $fields['lastname'],
            $fields['username'],
            $this->getEncoder(User::class)->encodePassword($fields['password'], ''),
            $fields['email']
        );

        $this->entityManager->getRepository(User::class)->persistSave($user);
    }

    /**
     * @param string $className
     *
     * @return PasswordEncoderInterface
     */
    private function getEncoder(string $className)
    {
        return $this->encoder->getEncoder($className);
    }

    /**
     * @return mixed|QuestionHelper
     */
    private function getQuestionHelper()
    {
        return $this->getHelper('question');
    }
}
