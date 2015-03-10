<?php

namespace TwigAnalyser\Token;

use TwigAnalyser\Exception\InvalidContentException;
use TwigAnalyser\Exception\InvalidSyntaxException;

/**
 * @package TwigAnalyser\Token
 */
class Tokenizer
{

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @param \Twig_Environment $environment
     */
    public function __construct(
        \Twig_Environment $environment
    ) {
        $this->environment = $environment;
    }

    /**
     * @param $content
     * @return \Twig_Token[]
     * @throws InvalidSyntaxException
     * @throws InvalidContentException
     */
    public function getTokens($content)
    {
        if (empty(trim($content))) {
            throw new InvalidContentException('Template content must be not empty');
        }

        try {
            $tokens = $this->environment->tokenize($content);
        } catch (\Twig_Error_Syntax $exception) {
            throw new InvalidSyntaxException('Content contains syntax errors.');
        }

        $tokensArray = [];

        do {
            $tokensArray[] = $tokens->getCurrent();

            $tokens->next();
        } while (!$tokens->isEOF());

        return $tokensArray;
    }
}
