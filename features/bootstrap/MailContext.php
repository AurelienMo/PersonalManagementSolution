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
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * Class MailContext
 */
class MailContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function purgeSpool()
    {
        $spoolDir = $this->getSpoolDir();

        $filesystem = new Filesystem();

        $filesystem->remove($spoolDir);
    }

    /**
     * @Given /^(?P<expectedCount>\d+) mails? should have been sent$/
     * @param $expectedCount
     * @throws Exception
     */
    public function xMailsShouldHaveBeenSent($expectedCount)
    {
        $spoolDir = $this->getSpoolDir();

        $filesystem = new Filesystem();

        $errorMessage = '%d mail(s) has been sent (%d expected)';
        if ($filesystem->exists($spoolDir)) {
            $finder = new Finder();

            $actualCount = $finder->in($spoolDir)->ignoreDotFiles(true)->count();
            if ($actualCount !== $expectedCount) {
                throw new \Exception(sprintf($errorMessage, $actualCount, $expectedCount));
            } else {
                return;
            }
        }

        if ($expectedCount > 0) {
            throw new \Exception(sprintf($errorMessage, 0, $expectedCount));
        }
    }

    /**
     * @Given /^a mail should have been sent to "(?P<email>(?:[^"]|\\")*)" with subject "(?P<subject>(?:[^"]|\\")*)"$/
     * @param $email
     * @param $subject
     * @throws Exception
     */
    public function aMailShouldHaveBeenSentToWithSubject($email, $subject)
    {
        $spoolDir = $this->getSpoolDir();

        $filesystem = new Filesystem();

        if ($filesystem->exists($spoolDir)) {
            $finder = new Finder();
            $finder->in($spoolDir)->ignoreDotFiles(true)->files();

            foreach ($finder as $fileInfo) {
                /** @var SplFileInfo $fileInfo */
                /** @var Swift_Message $message */
                $message = unserialize($fileInfo->getContents());

                if ($message->getSubject() !== $subject) {
                    continue;
                }

                $recipients = array_keys($message->getTo());
                if (!in_array($email, $recipients)) {
                    continue;
                }

                return;
            }
        }

        throw new \Exception(sprintf('No email was sent to "%s" with "%s" as subject', $email, $subject));
    }

    /**
     * @Given /^a mail should have been sent to "(?P<email>(?:[^"]|\\")*)" with subject "(?P<subject>(?:[^"]|\\")*)" with content:$/
     * @param              $email
     * @param              $subject
     * @param PyStringNode $contentNode
     * @throws Exception
     */
    public function aMailShouldHaveBeenSentToWithSubjectAndContent($email, $subject, PyStringNode $contentNode)
    {
        $spoolDir = $this->getSpoolDir();

        $filesystem = new Filesystem();

        if ($filesystem->exists($spoolDir)) {
            $finder = new Finder();
            $finder->in($spoolDir)->ignoreDotFiles(true)->files();

            foreach ($finder as $fileInfo) {
                /** @var SplFileInfo $fileInfo */
                /** @var Swift_Message $message */
                $message = unserialize($fileInfo->getContents());

                if ($message->getSubject() !== $subject) {
                    continue;
                }

                $recipients = array_keys($message->getTo());
                if (!in_array($email, $recipients)) {
                    continue;
                }

                $actualDomDocument = $this->getDomDocument($message->getBody());
                $expectedDomDocument = $this->getDomDocument($contentNode->getRaw());

                if ($actualDomDocument->saveHtml() !== $expectedDomDocument->saveHtml()) {
                    continue;
                }

                return;
            }
        }

        throw new \Exception(sprintf('No email was sent to "%s" with "%s" as subject with such content', $email, $subject));
    }

    /**
     * @return string
     */
    private function getSpoolDir(): string
    {
        $spoolDir = $this->kernel->getContainer()->getParameter('swiftmailer.spool.default.file.path');

        return $spoolDir;
    }

    /**
     * @param $string
     * @return DOMDocument
     */
    private function getDomDocument($string)
    {
        $domDocument = new DOMDocument();
        $string = preg_replace('/(\r|\n)/', ' ', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $domDocument->loadHTML($string);

        return $domDocument;
    }
}
