<?php

namespace Webasics\Tests\Integration;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Webasics\Framework\Http\Exception\HttpClientAdapterException;
use Webasics\Framework\Http\Adapter\CurlAdapter;
use Webasics\Framework\Http\HttpClient;

/**
 * Class ClientTest
 * @package Webasics\Tests\Integration
 */
class ClientTest extends TestCase
{

    /** @var string */
    private static string $serverProcess;

    public static function setUpBeforeClass(): void
    {
        $serverBin = realpath(__DIR__ . '/../../bin/server.sh');
        static::$serverProcess = shell_exec($serverBin);
        usleep(50000);
    }

    public static function tearDownAfterClass(): void
    {
        shell_exec('kill -9 ' . static::$serverProcess);
    }

    public function __destruct()
    {
        shell_exec('kill -9 ' . static::$serverProcess . ' &> /dev/null');
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return RequestInterface
     */
    private function createRequest(string $method, string $uri)
    {
        $factory = new Psr17Factory();
        return $factory->createRequest($method, $uri);
    }

    public function itShouldThrowAnExceptionForInvalidAdapter()
    {

    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldReturnAValidResponseForHttpGet()
    {
        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('GET', 'http://localhost:8000/test'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"data":[]}', (string)$response->getBody());
    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldReturnAValidResponseForHttpPost()
    {
        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('POST', 'http://localhost:8000'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"data":{"set":{"name":"test","value":"test"}}}', (string)$response->getBody());
    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldReturnAValidResponseForHttpPatch()
    {
        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('PATCH', 'http://localhost:8000/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"data":{"set":{"name":"test_patched","value":"test_patched"}}}', (string)$response->getBody());
    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldReturnAValidResponseForHttpDelete()
    {
        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('DELETE', 'http://localhost:8000'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"data":[]}', (string)$response->getBody());
    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldThrowAnExceptionForInvalidMethod()
    {
        self::expectException(HttpClientAdapterException::class);

        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('INVALID', 'http://localhost:8000'));
    }

    /**
     * @test
     *
     * @throws HttpClientAdapterException
     */
    public function itShouldThrowAnExceptionForInvalidPort()
    {
        self::expectException(HttpClientAdapterException::class);

        $client = new HttpClient(new CurlAdapter());
        $response = $client->sendRequest($this->createRequest('INVALID', 'http://localhost:9000'));
    }

}