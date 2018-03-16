<?php

use Illuminate\Support\Facades\Cache;

function nonce(string $directive = '')
{
    if ($directive === 'script-src') {
        return Cache::get('script_nonces', '');
    }

    if ($directive === 'style-src') {
        return Cache::get('style_nonces', '');
    }
}