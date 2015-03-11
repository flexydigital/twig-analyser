<?php

namespace TwigAnalyser\Extension;

use TwigAnalyser\Exception\InvalidContentException;
use TwigAnalyser\Exception\InvalidSyntaxException;
use TwigAnalyser\Factory\ExtensionCallFactory;
use TwigAnalyser\Token\Tokenizer;

/**
 * @package TwigAnalyser\Extension
 */
class CallAnalyser
{

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var ExtensionCallFactory
     */
    private $callFactory;

    /**
     * @param Tokenizer $tokenizer
     * @param ExtensionCallFactory $callFactory
     */
    public function __construct(
        Tokenizer $tokenizer,
        ExtensionCallFactory $callFactory
    ) {
        $this->tokenizer = $tokenizer;
        $this->callFactory = $callFactory;
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

        return $this->callFactory->createByTokens($extensionName, $tokens);
    }
}
