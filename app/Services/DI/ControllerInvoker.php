<?php declare(strict_types=1);


namespace App\Services\DI;

use DI\Bridge\Slim\ControllerInvoker as CI;
use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ControllerInvoker extends CI
{
    /**
     * @var InvokerInterface
     */
    protected $invoker;

    public function __construct(InvokerInterface $invoker)
    {
        $this->invoker = $invoker;
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        // Inject the request and response by parameter name
        $parameters = [
            'request'  => $request,
            'response' => $response,
            'args'     => $routeArguments
        ];
        // Inject the route arguments by name
        $parameters += $routeArguments;
        // Inject the attributes defined on the request
        $parameters += $request->getAttributes();

        return $this->invoker->call($callable, $parameters);
    }
}
