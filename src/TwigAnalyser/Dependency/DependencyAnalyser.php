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
    const EXTEND_TYPE = "extends";

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

                if ($token->getValue() == self::INCLUDE_TYPE || $token->getValue() == self::EXTEND_TYPE) {
                    $path = $tokens[$i+1]->getValue();

                    if ($this->hasDependencyWith($relations, $path)) {
                        continue;
                    }
                    
                    $relations[] = $this->dependencyFactory->create($path, $token->getValue());
                }
            }
        }

        return $relations;
    }

    /**
     * @param Dependency[] $relations
     * @param $path
     * @return bool
     */
    private function hasDependencyWith(array $relations, $path)
    {
        foreach ($relations as $relation) {
            if ($relation->path == $path) {
                return true;
            }
        }
        
        return false;
    }
}
