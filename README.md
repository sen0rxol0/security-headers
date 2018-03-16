# Security Headers

[![Packagist](https://img.shields.io/packagist/v/sen0rxol0/security-headers.svg)](https://packagist.org/packages/sen0rxol0/security-headers)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sen0rxol0/security-headers.svg)](https://packagist.org/packages/sen0rxol0/security-headers)
<!-- [![Packagist](https://img.shields.io/packagist/dt/sen0rxol0/security-headers.svg)](https://packagist.org/packages/sen0rxol0/security-headers) -->

This package aims to improve HTTP response headers security, built for integration with [Laravel](https://laravel.com).

It wont be a extend guide on headers security, for more info check out [resources](#resources) or start by testing your application headers on [securityheaders.io](https://securityheaders.io/) and come back when you probably :scream:

## Integration

Install the package with composer

```bash
  composer require sen0rxol0\security-headers
```

Publish the configuration file

```bash
  php artisan vendor:publish --tag="config"
```

Now that the config file may be published at `config\headers.php`

Add `SecurityHeadersMiddleware` to your application global middleware in `app\Http\Kernel.php`

```php
  protected $middleware = [
    //..
    \Sen0rxol0\SecurityHeaders\SecurityHeadersMiddleware::class,
  ];
```

Next you can start tweaking the config file in `config\headers.php`,
although i recommend [reading more](#resources) about security headers, the base config is good to go.

### Usage information

When using Content-Security-Policy with nonce or if `add-nonce` is set to `true` you will need to make use of a helper function in your templates script tags

```html
  <script nonce="{{ nonce('script-src') }}" src="{{ mix('/js/app.js') }}"></script>
```

## Resources

- [OWASP Secure Headers Project](https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#tab=Headers)
- [Hardening your HTTP Security Headers](https://www.keycdn.com/blog/http-security-headers/)
- [HTTP Headers on Mozilla](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers#Security)
- [Content Security Policy](https://csp.withgoogle.com/docs/index.html)
- [And also check out Scott Helme website](https://scotthelme.co.uk/)

## Credits

- [CSP-Builder](https://github.com/paragonie/csp-builder)
- [Laravel](https://github.com/laravel/laravel)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
