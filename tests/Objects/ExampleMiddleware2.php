<?php

namespace MiniRestFramework\Tests\Objects;

use Closure;
use MiniRestFramework\Http\Middlewares\MiddlewareInterface;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class ExampleMiddleware2 implements MiddlewareInterface
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->id <= 0) {
            return Response::json($request->isReal);
        }

        return $next($request);
    }
}