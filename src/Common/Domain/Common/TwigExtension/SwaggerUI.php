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

namespace App\Common\Domain\Common\TwigExtension;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SwaggerUI
 */
class SwaggerUI extends AbstractExtension
{
    const CACHE_CONTAINER_NAME = 'app_api_swagger_doc';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(CacheInterface $cache, SerializerInterface $serializer)
    {
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    /**
     * Return SwaggerUi function for Twig
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_swagger_doc_common', [$this, 'getSwaggerDoc'])
        ];
    }

    /**
     * Retrieve documentation from cache or reload it
     *
     * @return string
     */
    public function getSwaggerDoc(): string
    {
        return $this->loadSwaggerDoc();
    }

    /**
     * Load the SwaggerDoc
     * @return string
     */
    private function loadSwaggerDoc(): string
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/../../../../../config/common/swagger');
        $definition = "";
        foreach ($finder as $file) {
            /** @var string $path */
            $path = $file->getRealPath();
            $datas = Yaml::parseFile($path);
            if (null !== $datas) {
                $datas = $this->serializer->serialize($datas, 'json');
                $definition.= $datas;
            }
        }
        return $definition;
    }
}
