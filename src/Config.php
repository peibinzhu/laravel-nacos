<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use JetBrains\PhpStorm\ArrayShape;

class Config
{
    protected string $baseUri = 'http://127.0.0.1:8848';

    protected ?string $username = null;

    protected ?string $password = null;

    protected array $guzzleConfig = [
        'headers'     => [
            'charset' => 'UTF-8',
        ],
        'http_errors' => false,
    ];

    /**
     * @param array $config
     */
    public function __construct(
        #[ArrayShape([
            'base_uri'      => 'string', // http://127.0.0.1:8848
            'username'      => 'string',
            'password'      => 'string',
            'guzzle_config' => 'array',
        ])] array $config = []
    ) {
        isset($config['base_uri']) && $this->baseUri = (string)$config['base_uri'];
        isset($config['username']) && $this->username = (string)$config['username'];
        isset($config['password']) && $this->password = (string)$config['password'];
        isset($config['guzzle_config']) && $this->guzzleConfig = (array)$config['guzzle_config'];
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getGuzzleConfig(): array
    {
        return $this->guzzleConfig;
    }
}
