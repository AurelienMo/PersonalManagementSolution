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

namespace App\Actions\Web\Core;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Documentation
 *
 * @Route("/documentation", name="documentation")
 */
class Documentation
{
    /** @var Environment */
    protected $templating;

    /**
     * Documentation constructor.
     *
     * @param Environment $templating
     */
    public function __construct(
        Environment $templating
    ) {
        $this->templating = $templating;
    }

    /**
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke()
    {
        return new Response(
            $this->templating->render(
                'core/documentation.html.twig'
            )
        );
    }
}
