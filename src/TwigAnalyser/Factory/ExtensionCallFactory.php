<?php

namespace TwigAnalyser\Factory;

use TwigAnalyser\Extension\ExtensionCall;

/**
 * @package TwigAnalyser\Factory
 */
class ExtensionCallFactory
{

    /**
     * @param $extension
     * @param array $parameters
     * @return ExtensionCall
     */
    public function create($extension, array $parameters)
    {
        $dto = new ExtensionCall();
        $dto->extension = $extension;
        $dto->parameters = $parameters;
        return $dto;
    }

    /**
     *
     * @param $extension
     * @param \Twig_Token[] $tokens
     * @return ExtensionCall[]
     */
    public function createByTokens($extension, array $tokens)
    {
        $calls = [];

        for ($i=0; $i<count($tokens); $i++) {
            if ($this->isTwigExtensionCall($i, $tokens, $extension)) {
                $calls[] = $this->create(
                    $extension,
                    $this->nestleTokenGroup($this->getTokenGroup($i, $tokens, '(', ')'))
                );
            }
        }

        return $calls;
    }

    /**
     * @param \Twig_Token[] $tokens
     * @return array
     */
    private function nestleTokenGroup(array $tokens)
    {
        $values = [];

        for ($i=0; $i<count($tokens); $i++) {
            /** @var \Twig_Token $token */
            $token = $tokens[$i];

            if (($isCall = $this->isTwigExtensionCall($i, $tokens)) || $token->getValue() == '[') {
                $callTokens = $this->getTokenGroup($i, $tokens, $isCall ? '(' : '[', $isCall ? ')' : ']');
                $group = $this->nestleTokenGroup(array_splice($callTokens, 1, -1));
                $values[] = $isCall ? [$token->getValue() => $group] : $group;

                array_splice($tokens, $i, count($callTokens));
                continue;
            }

            if ($token->getType() != \Twig_Token::PUNCTUATION_TYPE) {
                $values[] = $token->getValue();
            }
        }

        return $values;
    }

    /**
     * @param $callTokenIndex
     * @param \Twig_Token[] $tokens
     * @param string $startDelimiter
     * @param string $endDelimiter
     * @return array
     */
    private function getTokenGroup($callTokenIndex, array $tokens, $startDelimiter, $endDelimiter)
    {
        $parametersTokens = [];
        $delimiterCounter = 0;
        $atLeastOneDelimiter = false;

        for ($i = $callTokenIndex; $i < count($tokens); $i++) {
            if ($tokens[$i]->getValue() == $startDelimiter) {
                $delimiterCounter++;
                $atLeastOneDelimiter = true;
            }

            if ($tokens[$i]->getValue() == $endDelimiter) {
                $delimiterCounter--;
            }

            if ($atLeastOneDelimiter) {
                $parametersTokens[] = $tokens[$i];

                if ($delimiterCounter == 0) {
                    break;
                }
            }
        }

        return $parametersTokens;
    }

    /**
     * @param $callTokenIndex
     * @param \Twig_Token[] $tokens
     * @param $extension
     * @return bool
     */
    private function isTwigExtensionCall($callTokenIndex, array $tokens, $extension = null)
    {
        $isBeforeParentheses = isset($tokens[$callTokenIndex+1])
            ? $tokens[$callTokenIndex+1]->getValue() == '('
            : false;

        $areWeLookingFor = $extension
            ? $tokens[$callTokenIndex]->getValue() == $extension
            : true;

        return $tokens[$callTokenIndex]->getType() == \Twig_Token::NAME_TYPE
            && $isBeforeParentheses
            && $areWeLookingFor;
    }
}
