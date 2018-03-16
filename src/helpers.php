<?php

function nonce(string $directive = ''): string
{
    function getNonceFromSession(string $key): string {
        if (!session()->exists($key)) {
            return ''; // @todo throw exception
        }

        $nonces = json_decode(session()->get($key));
        $nonce = $nonces[0];
        $nonces = array_slice($nonces, 0, 1);
        
        if (empty($nonces)) {
            session()->forget($key);
        } else {
            session([$key => json_encode($nonces)]);
        }

        return $nonce ?? "";
    }

    if ($directive === 'script-src') {
        return getNonceFromSession('script_nonces');
    }

    if ($directive === 'style-src') {
        return getNonceFromSession('style_nonces');
    }
}