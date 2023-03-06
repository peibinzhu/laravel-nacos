<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use Illuminate\Support\ServiceProvider;
use PeibinLaravel\ProviderConfig\Contracts\ProviderConfigInterface;

class NacosServiceProvider extends ServiceProvider implements ProviderConfigInterface
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                Application::class => ApplicationFactory::class,
            ],
            'publish'      => [
                [
                    'id'          => 'nacos',
                    'source'      => __DIR__ . '/../config/nacos.php',
                    'destination' => config_path('nacos.php'),
                ],
            ],
        ];
    }
}
