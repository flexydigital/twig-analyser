<?php

namespace TwigAnalyser\Dependency;

use TwigAnalyser\Exception\InvalidContentException;
use TwigAnalyser\Exception\InvalidSyntaxException;
use TwigAnalyser\Factory\DependencyFactory;
use TwigAnalyser\Token\Tokenizer;

/**
 * @package TwigAnalyser\Dependency
 */
class DependencyAnalyser
{
    const INCLUDE_TYPE = "include";
    const EXTEND_TYPE = "extend";

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var DependencyFactory
     */
    private $dependencyFactory;

    /**
     * @param Tokenizer $tokenizer
     * @param DependencyFactory $dependencyFactory
     */
    public function __construct(
        Tokenizer $tokenizer,
        DependencyFactory $dependencyFactory
    ) {
        $this->tokenizer = $tokenizer;
        $this->dependencyFactory = $dependencyFactory;
    }

    /**
     * @param $content
     * @return Dependency[]
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
                        $relations[] = $this->dependencyFactory->create($tokens[$i+1]->getValue(), self::INCLUDE_TYPE);
                        break;
                    case 'extends':
                        $relations[] = $this->dependencyFactory->create($tokens[$i+1]->getValue(), self::EXTEND_TYPE);
                        break;
                    default:
                        break;
                }
            }
        }

        return $relations;
    }
}
