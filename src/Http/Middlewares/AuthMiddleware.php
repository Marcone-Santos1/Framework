<?php

namespace MiniRest\Framework\Http\Middlewares;

use MiniRest\Framework\Auth\Auth;
use MiniRest\Framework\Exceptions\InvalidJWTToken;
use MiniRest\Framework\Helpers\StatusCode\StatusCode;
use MiniRest\Framework\Http\Request\Request;
use MiniRest\Framework\Http\Response\Response;

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
