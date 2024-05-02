<?php

namespace MiniRestFramework\Http\Middlewares;

use MiniRestFramework\Auth\Auth;
use MiniRestFramework\Exceptions\InvalidJWTToken;
use MiniRestFramework\Helpers\StatusCode\StatusCode;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $next)
    {
        try {
            if (Auth::check($request)) {
                return $next($request);
            } else {
                Response::json(['error' => 'Acesso não autorizado'], StatusCode::ACCESS_NOT_ALLOWED);
            }
        } catch (InvalidJWTToken $e) {
            Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
