<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class ReedExportAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     * @throws EntryNotFoundException
     * @throws BindingResolutionException
     */
    public function handle($request, Closure $next)
    {
        if (config('reedauth.users')->contains([$request->getUser(), $request->getPassword()])) {
            return $next($request);
        }

        return response('You shall not pass!', 401, ['WWW-Authenticate' => 'Basic']);
    }
}
