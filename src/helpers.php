<?php

function nonce(string $directive = '')
{
    if ($directive === 'script-src') {
        return request()->session()->get('script_nonces', '');
    }

    if ($directive === 'style-src') {
        return request()->session()->get('style_nonces', '');
    }
}