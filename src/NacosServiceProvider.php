<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use Illuminate\Support\ServiceProvider;
use PeibinLaravel\Utils\Providers\RegisterProviderConfig;

class NacosServiceProvider extends ServiceProvider
{
    use RegisterProviderConfig;

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
