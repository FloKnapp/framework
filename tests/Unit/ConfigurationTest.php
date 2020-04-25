<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\Config\Configuration;

/**
 * Class ConfigurationTest
 * @package Webasics\Tests\Unit
 */
class ConfigurationTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldResolveNamespace()
    {
        $config = new Configuration([
            'test' => 'test_value'
        ]);

        self::assertSame('test_value', $config->get('test'));
    }

    /**
     * @test
     */
    public function itShouldResolveNamespaceRecursive()
    {
        $config = new Configuration([
            'test' => [
                'test2' => 'test_value'
            ]
        ]);

        self::assertSame('test_value', $config->get('test:test2'));
    }

    /**
     * @test
     */
    public function itShouldResolveNamespaceRecursiveAgain()
    {
        $config = new Configuration([
            'test' => [
                'test2' => [
                    'test3' => 'test_value'
                ]
            ]
        ]);

        self::assertSame(['test3' => 'test_value'], $config->get('test:test2'));
        self::assertSame('test_value', $config->get('test:test2:test3'));
    }

    /**
     * @test
     */
    public function itShouldReturnNullForInvalidNamespace()
    {
        $config = new Configuration([
            'test' => [
                'test2' => [
                    'test3' => 'test_value'
                ]
            ]
        ]);

        self::assertNull($config->get('test:not_existent'));
    }

}