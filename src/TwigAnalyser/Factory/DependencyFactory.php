<?php

namespace TwigAnalyser\Factory;

use TwigAnalyser\Dependency\Dependency;

/**
 * @package TwigAnalyser\Factory
 */
class DependencyFactory
{

    /**
     * @param $path
     * @param $type
     * @return Dependency
     */
    public function create($path, $type)
    {
        $dto = new Dependency();
        $dto->path = $path;
        $dto->type = $type;

        return $dto;
    }
}
