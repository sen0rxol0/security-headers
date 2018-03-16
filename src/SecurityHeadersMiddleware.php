<?php

namespace Sen0rxol0\SecurityHeaders;

use Closure;
use Illuminate\Http\Request;
use Sen0rxol0\SecurityHeaders\SecurityHeadersBuilder;

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
        $nonces = $headers->getNonces();

        foreach ($policies as $key => $policy) {
            $response->headers->set($key, $policy, true);
        }

        // if (!empty($nonces)) {
        //     foreach ($nonces as $key => $nonce) {
        //         switch ($key) {
        //             case 'script':
        //                 \Config::set('script_nonce', $value);                 
        //                 break;
        //         }
        //     }
        // }

        return $response;
    }
}