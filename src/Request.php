<?php

namespace MedSchoolCoach\HttpClient;

use GuzzleHttp\Client;

class Request
{
    /**
     * @var string
     */
    private $bodyFormat;

    /**
     * @var array
     */
    private $headers;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->asJson();
    }

    /**
     * @param $headers
     * @return $this
     */
    public function withHeaders($headers)
    {
        return tap($this, function ($request) use ($headers) {
            foreach ($headers as $key => $value) {
                $this->headers[$key] = $value;
            }
        });
    }

    /**
     * @return mixed
     */
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
     * @param string $url
     * @param array $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $url, array $data = [])
    {
        return $this->send('GET', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $url, array $data = [])
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * @param string $url
     * @param array $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(string $url, array $data = [])
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
