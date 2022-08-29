<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PeibinLaravel\Nacos\Exception\RequestException;
use PeibinLaravel\Nacos\Providers\AccessToken;
use PeibinLaravel\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class AbstractProvider
{
    use AccessToken;

    public function __construct(protected Application $app, protected Config $config)
    {
    }

    public function request(string $method, string | UriInterface $uri, array $options = [])
    {
        $token = $this->getAccessToken();
        $token && $options[RequestOptions::QUERY]['accessToken'] = $token;
        return $this->client()->request($method, $uri, $options);
    }

    public function client(): Client
    {
        $config = array_merge($this->config->getGuzzleConfig(), [
            'base_uri' => $this->config->getBaseUri(),
        ]);

        return new Client($config);
    }

    protected function checkResponseIsOk(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return (string)$response->getBody() === 'ok';
    }

    protected function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $contents = (string)$response->getBody();
        if ($statusCode !== 200) {
            throw new RequestException($contents, $statusCode);
        }
        return Json::decode($contents);
    }

    protected function filter(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
