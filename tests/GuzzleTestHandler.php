<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use function GuzzleHttp\Psr7\parse_request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Filesystem\Filesystem;

class GuzzleTestHandler
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return PromiseInterface
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(RequestInterface $request, $options)
    {
        $data = $this->read($request);

        if ($data instanceof ResponseInterface) {
            if ($data->getStatusCode() > 300) {
                return new RejectedPromise(
                    new RequestException(
                        'Request exception ' . $data->getStatusCode(),
                        $request,
                        $data
                    )
                );
            }
            return new FulfilledPromise($data);
        }

        return new FulfilledPromise(new Response(200, [], $data));
    }

    /**
     * @param RequestInterface $request
     * @return string
     * @throws \ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function read(RequestInterface $request)
    {
        switch ($this->getRequestType($request)) {
            case 'api_hostname_test':
                list($testPrefix, $testId) = $this->handleApi($request);
                break;
            default:
                throw new \RuntimeException('Unable to handle request!');
        }

        try {
            $data = $this->getTestSample($testPrefix, $testId);
            return $data;
        } catch (\Throwable $e) {
            $this->createMockFile($request, $testPrefix, $testId);
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Files are touched so we can run cleanup by last modified date
     * @param string $testPrefix
     * @param int $testId
     * @return string
     */
    protected function getTestSample($testPrefix, $testId)
    {
        $basePath = $this->getBasePathMock();
        $filePathName = "{$basePath}/{$testPrefix}/{$testId}";

        if (file_exists("{$filePathName}.serialized")) {
            touch("{$filePathName}.serialized");
            $unserialized = file_get_contents("{$filePathName}.serialized");

            $object = \GuzzleHttp\Psr7\parse_response($unserialized);

            return $object;
        }

        if (file_exists("{$filePathName}.php")) {
            touch("{$filePathName}.php");
            return require("{$filePathName}.php");
        }

        if (file_exists("{$filePathName}.json")) {
            touch("{$filePathName}.json");
        }

        return file_get_contents("{$filePathName}.json");
    }

    /**
     * @return string
     */
    protected function getBasePathMock()
    {
        return $this->basePath;
    }

    /**
     * @param RequestInterface $request
     * @param mixed $testPrefix
     * @param mixed $testId
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function createMockFile(RequestInterface $request, $testPrefix, $testId)
    {
        $guzzleMain = new Client();

        try {
            $response = $guzzleMain->send($request);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        $response = \GuzzleHttp\Psr7\str($response);

        $fs = new Filesystem();

        $fs->dumpFile(
            "{$this->getBasePathMock()}/{$testPrefix}/{$testId}.serialized",
            $response
        );

        throw new \Exception("Missing mock file. File was created {$testPrefix}/{$testId}.serialized");
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function getRequestType(RequestInterface $request)
    {
        if (strpos($request->getUri()->getPath(), 'api_hostname_test') !== false) {
            return 'api_hostname_test';
        };

        return '';
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    private function handleApi(RequestInterface $request): array
    {
        $testPrefix = 'local/';
        $testId = trim($request->getUri()->getPath(), '/');

        return [$testPrefix, $testId];
    }

}
