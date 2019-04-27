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

namespace App\Domain\Common\Helpers;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailHelper.
 */
class MailHelper
{
    const PARAMS_MAIL_APPLICATION = [
        'email' => 'morvan.aurelien@gmail.com',
        'name' => 'Personal Management Solution',
    ];

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var Environment */
    protected $templating;

    /**
     * MailHelper constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Environment   $templating
     */
    public function __construct(
        \Swift_Mailer $mailer,
        Environment $templating
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param array  $from
     * @param array  $to
     * @param string $subject
     * @param string $template
     * @param array  $paramsView
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendMail(
        array $from,
        array $to,
        string $subject,
        string $template,
        array $paramsView = []
    ) {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from['email'], $from['name'] ?? null)
            ->setTo($to['email'], $to['name'] ?? null)
            ->setBody(
                $this->templating->render(
                    $template,
                    $paramsView
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
