<?php

declare(strict_types=1);

namespace Mem0\Mem0;

use Amp\Http\Client\HttpClient;
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Mem0\Contract\ClientInterface;
use Mem0\Exception\Mem0ApiException;

class Client implements ClientInterface
{
    private HttpClient $client;

    public function __construct(
        private string $apiKey,
        private string $apiUrl = 'https://api.mem0.ai',
    ) {
        $this->client = HttpClientBuilder::buildDefault();
    }

    public function sendRequest(
        string $method,
        string $endpoint,
        string $body = '',
        array $queryParams = []
    ): string
    {
        $url = "{$this->apiUrl}{$endpoint}";

        $request = new Request($url, $method);
        $request->setHeader('Authorization', "Token {$this->apiKey}");
        $request->setHeader('Content-Type', 'application/json');
        $request->setBody($body);
        $request->setQueryParameters($queryParams);

        $request->setTransferTimeout(160);
        $request->setInactivityTimeout(160);

        dump($url, $body, $queryParams);

        $response = $this->client->request($request);

        if ($response->getStatus() >= 300) {
            $error = null;
            try {
                $buffer = $response->getBody()->buffer();
                $error = $this->extractError($buffer);
            }  catch (\Throwable $e) {
                $buffer = "No body ({$e->getMessage()})";
            }

            dump($buffer);

            throw new Mem0ApiException(
                sprintf(
                    '[%s] Request to %s failed with status %d.%s',
                    $method,
                    $endpoint,
                    $response->getStatus(),
                    $error !== null ? " Error: {$error}" : '',
                ),
                statusCode: $response->getStatus(),
                responseBody: $buffer,
            );
        }

        $buffer = $response->getBody()->buffer();
        dump($buffer);

        return $buffer;
    }

    protected function extractError(string $body): ?string
    {
        try {
            $data = json_decode($body, true, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return null;
        }

        if (isset($data['error'])) {
            return $data['error'];
        }

        if (isset($data['message'])) {
            return $data['message'];
        }

        return null;
    }
}