<?php

namespace MiniRest\Framework\Http\Middlewares;

use Closure;
use MiniRest\Framework\Http\Request\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next);
}