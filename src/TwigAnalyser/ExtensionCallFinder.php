<?php

namespace TwigAnalyser;

use TwigAnalyser\Exception\InvalidContentException;
use TwigAnalyser\Exception\InvalidSyntaxException;
use TwigAnalyser\Factory\ExtensionCallDtoFactory;
use TwigAnalyser\Token\Tokenizer;

/**
 * @package TwigAnalyser
 */
class ExtensionCallFinder
{

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var ExtensionCallDtoFactory
     */
    private $callDtoFactory;

    /**
     * @param Tokenizer $tokenizer
     * @param ExtensionCallDtoFactory $callDtoFactory
     */
    public function __construct(
        Tokenizer $tokenizer,
        ExtensionCallDtoFactory $callDtoFactory
    ) {
        $this->tokenizer = $tokenizer;
        $this->callDtoFactory = $callDtoFactory;
    }

    /**
     * @param $content
     * @param $extensionName
     * @return array
     */
    public function findInContent($content, $extensionName)
    {
        try {
            $tokens = $this->tokenizer->getTokens($content);
        } catch (InvalidContentException $e) {
            $tokens = [];
        } catch (InvalidSyntaxException $e) {
            $tokens = [];
        }

        return $this->callDtoFactory->createByTokens($extensionName, $tokens);
    }
}
