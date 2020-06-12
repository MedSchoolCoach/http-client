<?php

namespace MedSchoolCoach\HttpClient;

use Psr\Http\Message\MessageInterface;

class Response
{
    /**
     * @var array
     */
    protected $decoded;

    /**
     * @var MessageInterface
     */
    protected $response;

    /**
     * Response constructor.
     * @param MessageInterface $response
     */
    public function __construct(MessageInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function json()
    {
        if (! $this->decoded) {
            $this->decoded = json_decode((string) $this->response->getBody(), true);
        }

        return $this->decoded;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return isset($this->json()[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->json()[$key];
    }
}
