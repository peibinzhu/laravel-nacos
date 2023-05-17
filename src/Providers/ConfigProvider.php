<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos\Providers;

use GuzzleHttp\RequestOptions;
use PeibinLaravel\Nacos\AbstractProvider;
use Psr\Http\Message\ResponseInterface;

class ConfigProvider extends AbstractProvider
{
    public const WORD_SEPARATOR = "\x02";

    public const LINE_SEPARATOR = "\x01";
    /**
     * A request to listen for data packets.
     * Format: dataId^group^2contentMD5^tenant^1 or dataId^group^2contentMD5^1.
     *
     * @var string
     */
    protected const LISTENING_CONFIGS_FORMAT = '%s%s%s%s%s%s%s%s';

    public function get(string $dataId, string $group, ?string $tenant): ResponseInterface
    {
        return $this->request('GET', '/nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant,
            ]),
        ]);
    }

    public function listener(
        string $dataId,
        string $group,
        ?string $contentMD5,
        ?string $tenant = null
    ): ResponseInterface {
        $configs = sprintf(
            self::LISTENING_CONFIGS_FORMAT,
            $dataId,
            self::WORD_SEPARATOR,
            $group,
            self::WORD_SEPARATOR,
            $contentMD5,
            self::WORD_SEPARATOR,
            $tenant,
            self::LINE_SEPARATOR
        );
        return $this->request('POST', '/nacos/v1/cs/configs/listener', [
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => 1000 * 30,
            ],
            RequestOptions::QUERY   => [
                'Listening-Configs' => $configs,
            ],
        ]);
    }

    public function set(
        string $dataId,
        string $group,
        string $content,
        ?string $type,
        ?string $tenant
    ): ResponseInterface {
        return $this->request('POST', '/nacos/v1/cs/configs', [
            RequestOptions::FORM_PARAMS => $this->filter([
                'dataId'  => $dataId,
                'group'   => $group,
                'tenant'  => $tenant,
                'type'    => $type,
                'content' => $content,
            ]),
        ]);
    }

    public function delete(string $dataId, string $group, ?string $tenant = null): ResponseInterface
    {
        return $this->request('DELETE', '/nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant,
            ]),
        ]);
    }
}
