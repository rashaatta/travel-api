<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guard($guards[0])->guest()) {
            $status = Response::HTTP_UNAUTHORIZED;
            $data = [
                "success" => false,
                "status" => $status,
                "error_message" => 'Unauthorized.',
                'errors' => ''
            ];

            return response($data, $status);
        }

        return $next($request);
    }
}
