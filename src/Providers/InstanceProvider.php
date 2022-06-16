<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos\Providers;

use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use PeibinLaravel\Nacos\AbstractProvider;
use PeibinLaravel\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

class InstanceProvider extends AbstractProvider
{
    /**
     * @param string $ip
     * @param int    $port
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function register(
        string $ip,
        int $port,
        string $serviceName,
        #[ArrayShape([
            'groupName'   => 'string',
            'clusterName' => 'string',
            'namespaceId' => 'string',
            'weight'      => 'float',
            'metadata'    => 'string',
            'enabled'     => 'bool',
            'ephemeral'   => 'bool', // 是否临时实例
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('POST', '/nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                    'ip'          => $ip,
                    'port'        => $port,
                ])
            ),
        ]);
    }

    /**
     * @param string $serviceName
     * @param string $groupName
     * @param string $ip
     * @param int    $port
     * @param array  $optional
     * @return ResponseInterface
     */
    public function delete(
        string $serviceName,
        string $groupName,
        string $ip,
        int $port,
        #[ArrayShape([
            'clusterName' => 'string',
            'namespaceId' => 'string',
            'ephemeral'   => 'bool',
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('DELETE', '/nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                    'groupName'   => $groupName,
                    'ip'          => $ip,
                    'port'        => $port,
                ])
            ),
        ]);
    }

    /**
     * @param string $ip
     * @param int    $port
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function update(
        string $ip,
        int $port,
        string $serviceName,
        #[ArrayShape([
            'groupName'   => 'string',
            'clusterName' => 'string',
            'namespaceId' => 'string',
            'weight'      => 'float',
            'metadata'    => 'string', // json
            'enabled'     => 'bool',
            'ephemeral'   => 'bool',
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('PUT', '/nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                    'ip'          => $ip,
                    'port'        => $port,
                ])
            ),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function list(
        string $serviceName,
        #[ArrayShape([
            'groupName'   => 'string',
            'namespaceId' => 'string',
            'clusters'    => 'string', // 集群名称(字符串，多个集群用逗号分隔)
            'healthyOnly' => false,
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('GET', '/nacos/v1/ns/instance/list', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'serviceName' => $serviceName,
                ])
            ),
        ]);
    }

    /**
     * @param string $ip
     * @param int    $port
     * @param string $serviceName
     * @param array  $optional
     * @return ResponseInterface
     */
    public function detail(
        string $ip,
        int $port,
        string $serviceName,
        #[ArrayShape([
            'groupName'   => 'string',
            'namespaceId' => 'string',
            'cluster'     => 'string',
            'healthyOnly' => false,
            'ephemeral'   => false,
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('GET', '/nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'ip'          => $ip,
                    'port'        => $port,
                    'serviceName' => $serviceName,
                ])
            ),
        ]);
    }

    /**
     * @param string      $serviceName
     * @param array       $beat
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @param bool|null   $ephemeral
     * @param bool        $lightBeatEnabled
     * @return ResponseInterface
     */
    public function beat(
        string $serviceName,
        #[ArrayShape([
            'ip'          => 'string',
            'port'        => 'int',
            'serviceName' => 'string',
            'cluster'     => 'string',
            'weight'      => 'int',
        ])] array $beat = [],
        ?string $groupName = null,
        ?string $namespaceId = null,
        ?bool $ephemeral = null,
        bool $lightBeatEnabled = false
    ): ResponseInterface {
        return $this->request('PUT', '/nacos/v1/ns/instance/beat', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'ip'          => $beat['ip'] ?? null,
                'port'        => $beat['port'] ?? null,
                'groupName'   => $groupName,
                'namespaceId' => $namespaceId,
                'ephemeral'   => $ephemeral,
                'beat'        => !$lightBeatEnabled ? Json::encode($beat) : '',
            ]),
        ]);
    }

    /**
     * @param string $ip
     * @param int    $port
     * @param string $serviceName
     * @param bool   $healthy
     * @param array  $optional
     * @return ResponseInterface
     */
    public function updateHealth(
        string $ip,
        int $port,
        string $serviceName,
        bool $healthy,
        #[ArrayShape([
            'namespaceId' => 'string',
            'groupName'   => 'string',
            'clusterName' => 'string',
        ])] array $optional = []
    ): ResponseInterface {
        return $this->request('PUT', '/nacos/v1/ns/health/instance', [
            RequestOptions::QUERY => $this->filter(
                array_merge($optional, [
                    'ip'          => $ip,
                    'port'        => $port,
                    'serviceName' => $serviceName,
                    'healthy'     => $healthy,
                ])
            ),
        ]);
    }
}
