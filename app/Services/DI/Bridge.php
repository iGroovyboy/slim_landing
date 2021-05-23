<?php declare(strict_types=1);


namespace App\Services\DI;

use Psr\Container\ContainerInterface;
use Slim\App;

use DI\Bridge\Slim\Bridge as DISlimBridge;
use Invoker\Invoker;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Invoker\ParameterResolver\DefaultValueResolver;
use Invoker\ParameterResolver\ResolverChain;

/* Decorator for DI\Bridge\Slim\Bridge to allow using
 * custom ControllerInvoker with Slim's $args
 */
class Bridge extends DISlimBridge
{
    /**
     * @param ContainerInterface $container
     * @return App
     */
    public static function createFromContainer(ContainerInterface $container): App
    {
        $app = parent::create($container);

        $controllerInvoker = static::createControllerInvoker($container);
        $app->getRouteCollector()->setDefaultInvocationStrategy($controllerInvoker);

        return $app;

//        return parent::create($container);
    }

    public static function createControllerInvoker(ContainerInterface $container): ControllerInvoker
    {
        $resolvers = [
            // Inject parameters by name first
            new AssociativeArrayResolver(),
            // Then inject services by type-hints for those that weren't resolved
            new TypeHintContainerResolver($container),
            // Then fall back on parameters default values for optional route parameters
            new DefaultValueResolver(),
        ];

        $invoker = new Invoker(new ResolverChain($resolvers), $container);

        return new ControllerInvoker($invoker);
    }
}
