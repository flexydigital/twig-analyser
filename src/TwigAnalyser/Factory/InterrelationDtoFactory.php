<?php

namespace TwigAnalyser;

use TwigAnalyser\Dto\InterrelationRelationDto;

/**
 * @author Jean Gonçalves <jean.goncalves@flexy.com.br>
 */
class TemplateRelationDtoFactory
{

    /**
     * @param $path
     * @param $type
     * @return InterrelationRelationDto
     */
    public function create($path, $type)
    {
        $dto = new InterrelationRelationDto();
        $dto->path = strtr($path, ['/' => '-']);
        $dto->type = $type;

        return $dto;
    }
}
