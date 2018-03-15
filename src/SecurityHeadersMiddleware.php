<?php

namespace Sen0roxol0\SecurityHeaders;

use Closure;
use Illuminate\Http\Request;


/**
*  Class SecurityHeadersMiddleware
*
*  @package Sen0rxol0/SecurityHeaders
*  @author Walter Varela @sen0rxol0
*/
class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $headers = new SecurityHeadersBuilder(config('headers', []));

        $policies = $headers->policies();


        // foreach ($nonces as $nonce)

        foreach ($policies as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }
}