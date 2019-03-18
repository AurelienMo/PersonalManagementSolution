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

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
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
}
