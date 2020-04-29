<?php

namespace Webasics\Framework\Http\Adapter;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Http\Exception\HttpClientAdapterException;

/**
 * Class Curl
 * @package Webasics\Framework\Http\Adapter
 */
class CurlAdapter implements AdapterInterface
{

    /** @var array */
    private array $responseHeaders;

    /**
     * @return bool
     */
    public function isSupported(): bool
    {
        return extension_loaded('curl');
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws HttpClientAdapterExceptio
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $scheme = $request->getUri()->getScheme();
        $host   = $request->getUri()->getHost();
        $path   = $request->getUri()->getPath();
        $uri    = $scheme . '://' . $host . $path;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_PORT, $request->getUri()->getPort());
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'handleResponseHeaders']);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux i686; rv:75.0) Gecko/20100101 Firefox/75.0';

        $formattedHeaders = $this->formatHeaders($headers);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);

        switch ($method) {

            case 'GET':
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, (string)$request->getBody());
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, (string)$request->getBody());
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                throw new HttpClientAdapterException('Invalid method given: ' . $method);
                break;

        }

        $responseRaw  = curl_exec($ch);

        if (false === $responseRaw) {
            throw new HttpClientAdapterException(curl_error($ch));
        }

        $responseInfo = curl_getinfo($ch);
        $statusCode   = $responseInfo['http_code'];

        $psr17Factory = new Psr17Factory();
        $response = $psr17Factory
            ->createResponse($statusCode)
            ->withBody(Stream::create($responseRaw));

        foreach ($this->responseHeaders as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        // Reset response headers due to the mechanism of collecting them
        $this->responseHeaders = [];

        curl_close($ch);

        return $response;

    }

    /**
     * @param $ch
     * @param $headerLine
     * @return int
     */
    private function handleResponseHeaders($ch, $headerLine)
    {
        if (strpos($headerLine, ':') === false) {
            return strlen($headerLine);
        }

        $cleanedHeaderLine = trim($headerLine);

        $segments = explode(':', $cleanedHeaderLine);

        list($field, $value) = $segments;

        $valueSegments = explode(';' , str_replace(' ', '', $value));

        $this->responseHeaders[$field] = $valueSegments;

        return strlen($headerLine);
    }

    /**
     * @param array $headers
     * @return array
     */
    private function formatHeaders(array $headers)
    {
        $result = [];

        foreach ($headers as $field => $value) {
            $result[] = $field . ': ' . $value;
        }

        return $result;
    }

}