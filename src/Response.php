<?php

namespace MedSchoolCoach\HttpClient;

use Psr\Http\Message\MessageInterface;

class Response
{
    protected ?array $decoded = null;

    protected MessageInterface $response;

    /**
     * Response constructor.
     * @param MessageInterface $response
     */
    public function __construct(MessageInterface $response)
    {
        $this->response = $response;
    }

    public function json(): array
    {
        if (! $this->decoded) {
            $this->decoded = json_decode((string) $this->response->getBody(), true);
        }

        return $this->decoded;
    }

    public function has(string $key): bool
    {
        return isset($this->json()[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->json()[$key];
    }
}
