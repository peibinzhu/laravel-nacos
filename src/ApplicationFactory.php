<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

class ApplicationFactory
{
    public function __invoke(Container $container): Application
    {
        $config = $container->get(Repository::class)->get('nacos', []);
        if (!empty($config['uri'])) {
            $baseUri = $config['uri'];
        } else {
            $baseUri = sprintf('http://%s:%d', $config['host'] ?? '127.0.0.1', $config['port'] ?? 8848);
        }

        return new Application(
            new Config([
                'base_uri'      => $baseUri,
                'username'      => $config['username'] ?? null,
                'password'      => $config['password'] ?? null,
                'guzzle_config' => $config['guzzle']['config'] ?? null,
            ])
        );
    }
}
