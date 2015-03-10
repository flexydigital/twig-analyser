<?php

namespace TwigAnalyser;

use TwigAnalyser\Factory\ExtensionCallDtoFactory;

/**
 * @package Flexy\Ftwo\Bundle\CommonBundle\Service\Template
 */
class ExtensionCallDtoFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtensionCallDtoFactory
     */
    private $factory;


    public function setUp()
    {
        $this->factory = new ExtensionCallDtoFactory();
    }

    public function testCreate()
    {
        print_r($this->factory->createByTokens('twig_extension_teste', $this->createTokens()));
    }

    /**
     * @return array
     */
    private function createTokens()
    {
        $tokens = [];
        $tokens[] = new \Twig_Token(\Twig_Token::NAME_TYPE, 'twig_extension_teste', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, '(', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::STRING_TYPE, 'parametro1', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ',', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::STRING_TYPE, 'parametro2', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ',', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, '[', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::STRING_TYPE, 'parametro3', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ',', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::NAME_TYPE, 'twig_extension_teste2', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, '(', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::STRING_TYPE, 'parametro4', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ')', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ']', 0);
        $tokens[] = new \Twig_Token(\Twig_Token::PUNCTUATION_TYPE, ')', 0);

        return $tokens;
    }
}
