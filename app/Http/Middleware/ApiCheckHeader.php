<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponder;
use Closure;


class ApiCheckHeader
{
    use ApiResponder;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accept_header = $request->header('Accept');

        if ($accept_header != 'application/json') {
            return $this->errorResponse(__('handler.ApiCheckHeaderException'), 400);
        }

        return $next($request);
    }
}
