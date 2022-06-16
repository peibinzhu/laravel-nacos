<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos\Providers;

use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use PeibinLaravel\Nacos\AbstractProvider;
use Psr\Http\Message\ResponseInterface;

class ServiceProvider extends AbstractProvider
{
    /**
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function create(
        string $serviceName,
        #[ArrayShape([
            'groupName'        => 'string',
            'namespaceId'      => 'string',
            'protectThreshold' => 'float',
            'metadata'         => 'string',
            'selector'         => 'string', // json字符串
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('POST', '/nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                ])
            ),
        ]);
    }

    public function delete(
        string $serviceName,
        ?string $groupName = null,
        ?string $namespaceId = null
    ): ResponseInterface {
        return $this->request('DELETE', '/nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName'   => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function update(
        string $serviceName,
        #[ArrayShape([
            'groupName'        => 'string',
            'namespaceId'      => 'string',
            'protectThreshold' => 'float',
            'metadata'         => 'string',
            'selector'         => 'string', // json字符串
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('PUT', '/nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                ])
            ),
        ]);
    }

    public function detail(
        string $serviceName,
        ?string $groupName = null,
        ?string $namespaceId = null
    ): ResponseInterface {
        return $this->request('GET', '/nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName'   => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    public function list(
        int $pageNo,
        int $pageSize,
        ?string $groupName = null,
        ?string $namespaceId = null
    ): ResponseInterface {
        return $this->request('GET', '/nacos/v1/ns/service/list', [
            RequestOptions::QUERY => $this->filter([
                'pageNo'      => $pageNo,
                'pageSize'    => $pageSize,
                'groupName'   => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }
}
