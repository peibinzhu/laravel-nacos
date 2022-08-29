<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos;

use InvalidArgumentException;
use PeibinLaravel\Nacos\Providers\AuthProvider;
use PeibinLaravel\Nacos\Providers\ConfigProvider;
use PeibinLaravel\Nacos\Providers\InstanceProvider;
use PeibinLaravel\Nacos\Providers\OperatorProvider;
use PeibinLaravel\Nacos\Providers\ServiceProvider;

/**
 * @property AuthProvider     $auth
 * @property ConfigProvider   $config
 * @property InstanceProvider $instance
 * @property OperatorProvider $operator
 * @property ServiceProvider  $service
 */
class Application
{
    protected array $alias = [
        'auth'     => AuthProvider::class,
        'config'   => ConfigProvider::class,
        'instance' => InstanceProvider::class,
        'operator' => OperatorProvider::class,
        'service'  => ServiceProvider::class,
    ];

    protected array $providers = [];

    public function __construct(protected Config $config)
    {
    }

    public function __get($name)
    {
        if (!isset($name) || !isset($this->alias[$name])) {
            throw new InvalidArgumentException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $class = $this->alias[$name];
        return $this->providers[$name] = new $class($this, $this->config);
    }
}
