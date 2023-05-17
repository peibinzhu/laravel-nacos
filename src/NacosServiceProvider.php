<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class NacosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $dependencies = [
            Application::class => ApplicationFactory::class,
        ];
        $this->registerDependencies($dependencies);
        $this->registerPublishing();
    }

    private function registerDependencies(array $dependencies)
    {
        $config = $this->app->get(Repository::class);
        foreach ($dependencies as $abstract => $concrete) {
            $concreteStr = is_string($concrete) ? $concrete : gettype($concrete);
            if (is_string($concrete) && method_exists($concrete, '__invoke')) {
                $concrete = function () use ($concrete) {
                    return $this->app->call($concrete . '@__invoke');
                };
            }
            $this->app->singleton($abstract, $concrete);
            $config->set(sprintf('dependencies.%s', $abstract), $concreteStr);
        }
    }

    public function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/nacos.php' => config_path('nacos.php'),
            ], 'nacos');
        }
    }
}
