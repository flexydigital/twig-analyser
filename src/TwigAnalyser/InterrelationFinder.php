<?php

namespace TwigAnalyser;

use TwigAnalyser\Dto\InterrelationRelationDto;
use TwigAnalyser\Exception\InvalidContentException;
use TwigAnalyser\Exception\InvalidSyntaxException;
use TwigAnalyser\Token\Tokenizer;

/**
 * @package TwigAnalyser
 */
class InterrelationFinder
{
    const INCLUDE_TYPE = "include";
    const EXTEND_TYPE = "extend";

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var TemplateRelationDtoFactory
     */
    private $relationDtoFactory;

    /**
     * @param Tokenizer $tokenizer
     * @param TemplateRelationDtoFactory $relationDtoFactory
     */
    public function __construct(
        Tokenizer $tokenizer,
        TemplateRelationDtoFactory $relationDtoFactory
    ) {
        $this->tokenizer = $tokenizer;
        $this->relationDtoFactory = $relationDtoFactory;
    }

    /**
     * @param $content
     * @return InterrelationRelationDto[]
     */
    public function findInContent($content)
    {
        $relations = [];

        try {
            $tokens = $this->tokenizer->getTokens($content);
        } catch (InvalidContentException $e) {
            $tokens = [];
        } catch (InvalidSyntaxException $e) {
            $tokens = [];
        }

        for ($i=0; $i<count($tokens); $i++) {
            $token = $tokens[$i];

            if ($token->getType() == \Twig_Token::NAME_TYPE) {
                switch($token->getValue()) {
                    case 'include':
                        $relations[] = $this->relationDtoFactory->create($tokens[$i+1]->getValue(), self::INCLUDE_TYPE);
                        break;
                    case 'extends':
                        $relations[] = $this->relationDtoFactory->create($tokens[$i+1]->getValue(), self::EXTEND_TYPE);
                        break;
                    default:
                        break;
                }
            }
        }

        return $relations;
    }
}
