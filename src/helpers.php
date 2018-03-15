<?php
function nonce(string $directive = '')
{
    if ($directive === 'script-src') {
        return app()->offsetGet('scriptnonce');
    }

    if ($directive === 'style-src') {
        return app()->make('shnonce.style');
    }
}