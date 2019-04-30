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

use App\Entity\AbstractEntity;
use App\Entity\CategoryTask;
use App\Entity\Group;
use App\Entity\Task;
use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class DoctrineContext
 */
class DoctrineContext implements Context
{
    /** @var SchemaTool */
    private $schemaTool;

    /** @var RegistryInterface */
    private $doctrine;

    /** @var KernelInterface */
    private $kernel;

    /** @var EncoderFactoryInterface|EncoderFactory */
    private $encoderFactory;

    /**
     * DoctrineContext constructor.
     *
     * @param RegistryInterface            $doctrine
     * @param KernelInterface              $kernel
     * @param EncoderFactoryInterface      $encoderFactory
     */
    public function __construct(
        RegistryInterface $doctrine,
        KernelInterface $kernel,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->doctrine = $doctrine;
        $this->schemaTool = new SchemaTool($this->doctrine->getManager());
        $this->kernel = $kernel;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @BeforeScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function clearDatabase()
    {
        $this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
        $this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->doctrine->getManager();
    }

    /**
     * @Given I load production file
     */
    public function iLoadProductionFile()
    {
        $app = new Application($this->kernel);
        $app->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command' => 'app:load-prod-fixtures',
            ]
        );
        $output = new BufferedOutput();
        $app->run($input, $output);
    }

    /**
     * @param AbstractEntity $entity
     * @param string         $uuid
     *
     * @throws ReflectionException
     */
    private function setUuid(AbstractEntity $entity, string $uuid)
    {
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $uuid);
    }

    /**
     * @Then :number entries should be exist into database for :class object
     *
     * @throws Exception
     */
    public function entriesShouldBeExistIntoDatabaseForObject($number, $class)
    {
        $count = $this->getManager()->getRepository($class)
            ->findAll();

        if (count($count) !== (int) $number) {
            throw new Exception(
                sprintf("%s object(s) has found into database, %s expected", count($count), (int) $number)
            );
        }
    }

    private function getEncoder()
    {
        return $this->encoderFactory->getEncoder(User::class);
    }

    /**
     * @Given I load following users:
     */
    public function iLoadFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $hash) {
            $user = new User(
                $hash['firstname'],
                $hash['lastname'],
                $hash['username'],
                $this->getEncoder()->encodePassword($hash['password'], ''),
                $hash['email'],
                $hash['tokenActivation'],
                $hash['status']
            );
            $this->getManager()->persist($user);
        }

        $this->getManager()->flush();
    }

    /**
     * @Then user with username :username should exist into database
     */
    public function userWithUsernameShouldExistIntoDatabase($username)
    {
        $user = $this->getManager()->getRepository(User::class)
                                   ->loadUserByUsername($username);

        if (is_null($user)) {
            throw new Exception(
                sprintf(
                    "User with username %s should exist",
                    $username
                )
            );
        }
    }

    /**
     * @Then user with username :username should have status :status
     */
    public function userWithUsernameShouldHaveStatus($username, $status)
    {
        /** @var User $user */
        $user = $this->getManager()->getRepository(User::class)
            ->loadUserByUsername($username);

        if ($user->getStatus() !== $status) {
            throw new Exception(
                sprintf(
                    "User should have status '%s', '%s' status occured",
                    $status,$user->getStatus()
                )
            );
        }
    }

    /**
     * @Given I load following group:
     *
     * @param TableNode $table
     *
     * @throws NonUniqueResultException
     */
    public function iLoadFollowingGroup(TableNode $table)
    {
        foreach ($table->getHash() as $hash) {
            /** @var User $user */
            $user = $this->getManager()->getRepository(User::class)->loadUserByUsername($hash['owner']);
            $group = new Group(
                $hash['name'],
                $hash['passwordToJoin'],
                $user
            );
            $this->getManager()->persist($group);
            $user->defineGroup($group);
        }

        $this->getManager()->flush();
    }

    /**
     * @Given group with name :name should have id :id
     *
     * @param string $name
     * @param string $id
     *
     * @throws ReflectionException
     */
    public function groupWithNameShouldHaveId(string $name, string $id)
    {
        $group = $this->getManager()->getRepository(Group::class)->findOneBy(['name' => $name]);
        $this->setUuid($group, $id);
        $this->getManager()->flush();
    }

    /**
     * @Given user :username should have group with id :groupId
     */
    public function userShouldHaveGroupWithId($username, $groupId)
    {
        /** @var User $user */
        $user = $this->getManager()->getRepository(User::class)->loadUserByUsername($username);
        $user->defineGroup(
            $this->getManager()->getRepository(Group::class)->find($groupId)
        );
        $this->getManager()->flush();
    }

    /**
     * @Given user with username :username should have following id :id
     */
    public function userWithUsernameShouldHaveFollowingId($username, $id)
    {
        $user = $this->getManager()->getRepository(User::class)->loadUserByUsername($username);
        $this->setUuid($user, $id);
        $this->getManager()->flush();
    }

    /**
     * @Given user with username :username should have following group name :name
     */
    public function userWithUsernameShouldHaveFollowingGroupName($username, $name)
    {
        /** @var User $user */
        $user = $this->getManager()->getRepository(User::class)->loadUserByUsername($username);
        $group = $this->getManager()->getRepository(Group::class)->findOneBy(['name' => $name]);
        $user->defineGroup($group);

        $this->getManager()->flush();
    }

    /**
     * @Given user with username :username should have following role:
     */
    public function userWithUsernameShouldHaveFollowingRole($username, TableNode $table)
    {
        /** @var User $user */
        $user = $this->getManager()->getRepository(User::class)->loadUserByUsername($username);
        foreach ($user->getRoles() as $role) {
            $user->updateRole($role, true);
        }
        foreach ($table->getHash() as $hash) {
            $user->updateRole($hash['role']);
        }
        $this->getManager()->flush();
    }

    /**
     * @Given I load following task categories:
     *
     * @param TableNode $table
     *
     * @throws Exception
     */
    public function iLoadFollowingTaskCategories(TableNode $table)
    {
        foreach ($table->getHash() as $hash) {
            $categoryTask = new CategoryTask($hash['name']);
            $this->getManager()->persist($categoryTask);
        }

        $this->getManager()->flush();
    }

    /**
     * @Then database should count :arg1 entry for entity :arg2 into database
     */
    public function databaseShouldCountEntryForEntityIntoDatabase($arg1, $arg2)
    {
        $resIntoDb = count($this->getManager()->getRepository($arg2)->findAll());
        if ((int) $resIntoDb !== (int) $arg1) {
            throw new Exception(
                sprintf(
                    'Not equal, %s expected, %s found into database',
                    $arg1,
                    $resIntoDb
                )
            );
        }
    }

    /**
     * @Then task with name :name should be exist and affect to :fullname
     */
    public function taskWithNameShouldBeExistAndAffectTo($name, $fullname)
    {
        $task = $this->getManager()->getRepository(Task::class)
                                   ->findOneBy(
                                       [
                                           'name' => $name,
                                       ]
                                   );

        /** @var User $userAffect */
        $userAffect = $task->getPersonAffected();

        if (is_null($userAffect)) {
            throw new Exception('Task not affected');
        }

        $fullNameAffected = sprintf('%s %s', $userAffect->getFirstname(), $userAffect->getLastname());

        if ($fullNameAffected !== $fullname) {
            throw new Exception(
                sprintf('%s expected, %s given', $fullNameAffected, $fullname)
            );
        }
    }

    /**
     * @Then task with name :name should be exist and display in group
     */
    public function taskWithNameShouldBeExistAndDisplayInGroup($name)
    {
        $task = $this->getManager()->getRepository(Task::class)
            ->findOneBy(
                [
                    'name' => $name,
                ]
            );

        if (is_null($task)) {
            throw new Exception('Task not exist');
        }

        if (!$task->isDisplayInGroup()) {
            throw new Exception('Task should be display in group');
        }

        if (!\is_null($task->getPersonAffected())) {
            throw new Exception('Task should be not affected');
        }
    }

    /**
     * @Then task with name :arg1 should be exist, display in group and affect to :fullname
     */
    public function taskWithNameShouldBeExistDisplayInGroupAndAffectTo($name, $fullname)
    {
        $task = $this->getManager()->getRepository(Task::class)
            ->findOneBy(
                [
                    'name' => $name,
                ]
            );

        if (is_null($task)) {
            throw new Exception('Task not exist');
        }

        if (!$task->isDisplayInGroup()) {
            throw new Exception('Task should be display in group');
        }

        /** @var User $userAffect */
        $userAffect = $task->getPersonAffected();
        $fullNameAffected = sprintf('%s %s', $userAffect->getFirstname(), $userAffect->getLastname());

        if ($fullNameAffected !== $fullname) {
            throw new Exception(
                sprintf('%s expected, %s given', $fullNameAffected, $fullname)
            );
        }
    }
}
