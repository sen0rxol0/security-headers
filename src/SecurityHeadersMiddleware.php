<?php

namespace Sen0roxol0\SecurityHeaders;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
*  A sample class
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
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

        $headers = (new SecurityHeadersBuilder(config('security-headers', [])))->headers();

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }
}