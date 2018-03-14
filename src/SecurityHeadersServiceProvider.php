<?php

namespace Sen0rxol0\SecurityHeaders;

use Illuminate\Support\ServiceProvider;


/**
*  Class SecurityHeadersServiceProvider
*
*  @package Sen0rxol0/SecurityHeaders
*  @author Walter Varela @sen0rxol0
*/
class SecurityHeadersServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/security-headers.php' => config_path('security-headers.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/security-headers.php', 'security-headers'
        );
    }
}