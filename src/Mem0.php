<?php

declare(strict_types=1);

namespace Mem0;

use DateTimeInterface;
use Mem0\Contract\ClientInterface;
use Mem0\Contract\SerializerInterface;
use Mem0\DTO\AddMemoriesRequest;
use Mem0\DTO\AddMemoryResponse;
use Mem0\DTO\AddMemoryResponseItem;
use Mem0\DTO\Filter;
use Mem0\DTO\ListMemoriesRequest;
use Mem0\DTO\Memory;
use Mem0\DTO\Message;
use Mem0\DTO\SearchMemoriesRequest;
use Mem0\Enum\ApiVersion;
use Mem0\Enum\OutputFormat;
use Mem0\Enum\Role;
use Mem0\Exception\Mem0ApiException;
use Mem0\Mem0\Client;
use Mem0\Mem0\Serializer;

class Mem0
{
    private ClientInterface $client;
    private SerializerInterface $serializer;

    /**
     * @param string                   $apiKey           API Key for authentication
     * @param string                   $apiUrl           base URL for the Mem0 API
     * @param string|null              $defaultOrgId     optional default Organization ID to use for requests
     * @param string|null              $defaultProjectId optional default Project ID to use for requests
     * @param ClientInterface|null     $client           optional custom HTTP client
     * @param SerializerInterface|null $serializer       optional custom serializer
     */
    public function __construct(
        private readonly string $apiKey,
        private readonly string $apiUrl = 'https://api.mem0.ai',
        private readonly ?string $defaultOrgId = null,
        private readonly ?string $defaultProjectId = null,
        ?ClientInterface $client = null,
        ?SerializerInterface $serializer = null,
    ) {
        $this->client = $client ?? new Client(
            apiKey: $this->apiKey,
            apiUrl: $this->apiUrl,
        );

        $this->serializer = $serializer ?? new Serializer();
    }

    /**
     * Get all memories, with optional filters, pagination, and field selection.
     * Corresponds to POST /v2/memories/.
     *
     * @param Filter|null        $filters   Filters to apply to the memories. Sent in the request body.
     * @param array<string>|null $fields    A list of field names to include in the response. Sent as query parameters.
     * @param int|null           $page      Page number for pagination. Default: 1. Sent as a query parameter.
     * @param int|null           $pageSize  Number of items per page. Default: 100. Sent as a query parameter.
     * @param string|null        $orgId     Filter memories by organization ID. Overrides client-level default. Sent in the request body.
     * @param string|null        $projectId Filter memories by project ID. Overrides client-level default. Sent in the request body.
     *
     * @throws Mem0ApiException If the API returns an error (e.g., 400 Bad Request).
     *
     * @return array<Memory> an array of Memory objects
     */
    public function listMemories(
        ?Filter $filters = null,
        ?array $fields = null,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $orgId = null,
        ?string $projectId = null
    ): array {
        $request = new ListMemoriesRequest(
            filters: $filters,
            fields: $fields,
            orgId: $orgId ?? $this->defaultOrgId,
            projectId: $projectId ?? $this->defaultProjectId,
        );

        $queryParams = [];
        if ($page !== null) {
            $queryParams['page'] = $page;
        }

        if ($pageSize !== null) {
            $queryParams['page_size'] = $pageSize;
        }

        if ($fields !== null) {
            $queryParams['fields'] = implode(',', $fields);
        }

        $jsonBody = $this->serializer->serialize($request);

        $responseJson = $this->client->sendRequest('POST', '/v2/memories/', $jsonBody, $queryParams);

        return $this->serializer->deserialize(
            $responseJson,
            Memory::class,
            true
        );
    }

    /**
     * Processes memory input.
     *
     * @param string|array<Message>  $messages           An array of message objects representing the content of the memory.
     *                                                   Each message object typically contains 'role' and 'content' fields,
     *                                                   where 'role' indicates the sender (e.g., 'user', 'assistant', 'system')
     *                                                   and 'content' contains the actual message text. This structure allows for
     *                                                   the representation of conversations or multi-part memories.
     *                                                   Each item is an object with string properties.
     * @param string|null            $agentId            the unique identifier of the agent associated with this memory
     * @param string|null            $userId             the unique identifier of the user associated with this memory
     * @param string|null            $appId              the unique identifier of the application associated with this memory
     * @param string|null            $runId              the unique identifier of the run associated with this memory
     * @param array|null             $metadata           Additional metadata associated with the memory, which can be used
     *                                                   to store any additional information or context about the memory.
     *                                                   Best practice for incorporating additional information is through
     *                                                   metadata (e.g. location, time, ids, etc.). During retrieval, you can
     *                                                   either use these metadata alongside the query to fetch relevant
     *                                                   memories or retrieve memories based on the query first and then
     *                                                   refine the results using metadata during post-processing.
     * @param string|null            $includes           String to include the specific preferences in the memory. (minLength: 1)
     * @param string|null            $excludes           String to exclude the specific preferences in the memory. (minLength: 1)
     * @param bool                   $infer              Whether to infer the memories or directly store the messages. Defaults to true.
     * @param OutputFormat|null      $outputFormat       It two output formats: v1.0 (default) and v1.1.
     *                                                   We recommend using v1.1 as v1.0 will be deprecated soon. Defaults to "v1.0".
     * @param array|null             $customCategories   a list of categories with category name and its description
     * @param string|null            $customInstructions Defines project-specific guidelines for handling and organizing
     *                                                   memories. When set at the project level, they apply to all new
     *                                                   memories in that project.
     * @param bool                   $immutable          Whether the memory is immutable. Defaults to false.
     * @param int|null               $timestamp          The timestamp of the memory. Format: Unix timestamp.
     * @param DateTimeInterface|null $expirationDate     The date and time when the memory will expire. Format: YYYY-MM-DD.
     * @param string|null            $orgId              the unique identifier of the organization associated with this memory
     * @param string|null            $projectId          the unique identifier of the project associated with this memory
     * @param ApiVersion|null        $version            The version of the memory to use. The default version is v1,
     *                                                   which is deprecated. We recommend using v2 for new applications.
     *
     * @return array<AddMemoryResponseItem>
     */
    public function add(
        null|array|string $messages = null,
        ?string $agentId = null,
        ?string $userId = null,
        ?string $appId = null,
        ?string $runId = null,
        ?array $metadata = null,
        ?string $includes = null,
        ?string $excludes = null,
        bool $infer = true,
        ?array $customCategories = null,
        ?string $customInstructions = null,
        bool $immutable = false,
        ?int $timestamp = null,
        ?DateTimeInterface $expirationDate = null,
        ?string $orgId = null,
        ?string $projectId = null,
    ): array {
        if (($agentId ?? $userId ?? $appId ?? $runId) === null) {
            throw new Mem0ApiException('At least one of the filters: agentId, userId, appId, runId is required');
        }

        if (is_string($messages)) {
            $messages = [new Message(
                role: Role::USER,
                content: $messages
            )];
        }

        $payload = new AddMemoriesRequest(
            messages: $messages,
            agentId: $agentId,
            userId: $userId,
            appId: $appId,
            runId: $runId,
            metadata: $metadata,
            includes: $includes,
            excludes: $excludes,
            infer: $infer,
            outputFormat: OutputFormat::V1_1,
            customCategories: $customCategories,
            customInstructions: $customInstructions,
            immutable: $immutable,
            timestamp: $timestamp,
            expirationDate: $expirationDate,
            orgId: $orgId ?? $this->defaultOrgId,
            projectId: $projectId ?? $this->defaultProjectId,
            version: ApiVersion::V2
        );

        $jsonBody = $this->serializer->serialize($payload);

        $responseJson = $this->client->sendRequest('POST', '/v1/memories/', $jsonBody);

        $response = $this->serializer->deserialize($responseJson, AddMemoryResponse::class);

        return $response->results;
    }

    /**
     * Search memories based on a query and filters using the v2 search API.
     * Supports complex logical operations (AND, OR, NOT) and comparison operators.
     *
     * @param string             $query          the query to search for in the memory
     * @param Filter             $filters        A dictionary of filters to apply to the search. Supports logical operators (AND, OR) and comparison operators (in, gte, lte, gt, lt, ne, contains, icontains).
     * @param int|null           $topK           The number of top results to return. Default: 10.
     * @param array<string>|null $fields         A list of field names to include in the response. If not provided, all fields will be returned.
     * @param bool|null          $rerank         Whether to rerank the memories. Default: false.
     * @param bool|null          $keywordSearch  Whether to search for memories based on keywords. Default: false.
     * @param bool|null          $filterMemories Whether to filter the memories. Default: false.
     * @param float|null         $threshold      The minimum similarity threshold for returned results. Default: 0.3.
     * @param string|null        $orgId          the unique identifier of the organization associated with the memory
     * @param string|null        $projectId      the unique identifier of the project associated with the memory
     *
     * @throws Mem0ApiException if the API returns an error
     *
     * @return array<Memory> an array of Memory objects matching the search criteria
     */
    public function search(
        string $query,
        Filter $filters,
        ?int $topK = null,
        ?array $fields = null,
        ?bool $rerank = null,
        ?bool $keywordSearch = null,
        ?bool $filterMemories = null,
        ?float $threshold = null,
        ?string $orgId = null,
        ?string $projectId = null
    ): array {
        $request = new SearchMemoriesRequest(
            query: $query,
            filters: $filters,
            topK: $topK,
            fields: $fields,
            rerank: $rerank,
            keywordSearch: $keywordSearch,
            filterMemories: $filterMemories,
            threshold: $threshold,
            orgId: $orgId ?? $this->defaultOrgId,
            projectId: $projectId ?? $this->defaultProjectId,
        );

        $jsonBody = $this->serializer->serialize($request);

        $responseJson = $this->client->sendRequest('POST', '/v2/memories/search/', $jsonBody);

        return $this->serializer->deserialize(
            $responseJson,
            Memory::class,
            true
        );
    }
}