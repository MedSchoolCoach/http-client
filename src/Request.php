<?php

namespace MedSchoolCoach\HttpClient;

use GuzzleHttp\Client;

class Request
{
    private string $bodyFormat;
    private array $headers;

    public function __construct()
    {
        $this->asJson();
    }

    /**
     * @return $this
     */
    public function withHeaders(array $headers): static
    {
        return tap($this, function ($request) use ($headers) {
            foreach ($headers as $key => $value) {
                $this->headers[$key] = $value;
            }
        });
    }

    public function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function bodyFormat(string $format)
    {
        return tap($this, function ($request) use ($format) {
            $this->bodyFormat = $format;
        });
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function contentType(string $contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $url, array $data = []): Response
    {
        return $this->send('GET', $url, $data);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $url, array $data = []): Response
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(string $url, array $data = []): Response
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $method, string $url, array $options = [])
    {
        $client = new Client([
            'cookies' => true,
        ]);

        if ($this->headers) {
            $options['headers'] = $this->headers;
        }

        return new Response(
            $client->request($method, $url, $options));
    }
}


if( !function_exists('tap')) {
	function tap($value, $callback)
	{
		$callback($value);

		return $value;
	}
}
