<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos\Providers;

use GuzzleHttp\RequestOptions;
use PeibinLaravel\Nacos\AbstractProvider;
use Psr\Http\Message\ResponseInterface;

class AuthProvider extends AbstractProvider
{
    public function login(string $username, string $password): ResponseInterface
    {
        return $this->client()->request('POST', '/nacos/v1/auth/users/login', [
            RequestOptions::QUERY       => [
                'username' => $username,
            ],
            RequestOptions::FORM_PARAMS => [
                'password' => $password,
            ],
        ]);
    }
}
