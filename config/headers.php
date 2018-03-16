<?php

return [
    /**
     * X-Content-Type-Options
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xcto
     * Possible values: 'nosniff'
     */
    'x-content-type-options' => 'nosniff',

    /**
     * X-Frame-Options
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xfo
     * Posible values: 'deny', 'sameorigin', 'allow-from <domain>'
     */
    'x-frame-options' => 'deny',

    /**
     * X-Permitted-Cross-Domain-Policies
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xpcdp
     * Possible values: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */
    'x-permitted-cross-domain-policies' => 'none',

    /**
     * X-XSS-Protection
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xxxsp
     * Possible values: '1', '0', '1; mode=block', '1; report="http://example.com/report_URI"'
     */
    'x-xss-protection' => '1; mode=block',

    /**
     * Referrer-Policy
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#rp
     * Possible values: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */
    'referrer-policy' => 'no-referrer',

    /**
     * HTTP Strict Transport Security
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#hsts
     * Ensure that your app is under https before enabling HSTS.
     */
    'hsts' => [
        'enabled' => false,
        'preload' => false,
        'max-age' => 31536000,
        'include-subdomains' => true
    ],

    /**
     * Expect-CT
     * 
     * References: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
     * https://scotthelme.co.uk/a-new-security-header-expect-ct/
     */
    'ect' => [
        'enabled' => false,
        'max-age' => 30,
        'enforce' => true,
        'report-uri' => ''
    ],

    /**
     * HTTP Public Key Pinning
     * References: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#hpkp
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/Public_Key_Pinning
     * 
     * HPKP will be ignored if hashes are not provided, 
     * hashes must be generate from your server cetificates files
     * 
     * HPKP will probably be deprecated
     * Read more: https://groups.google.com/a/chromium.org/forum/#!msg/blink-dev/he9tr7p3rZ8/eNMwKPmUBAAJ
     */
    'hpkp' => [
        // hashes are required
        'hashes' => [
            // [
            //     'algo' => 'sha256', // Browsers currently only support sha256 public key pins.
            //     'hash' => 'base64-encoded hash',
            // ],
        ],
        'include-subdomains' => false,
        'max-age' => 5184000,
        'report-only' => false,
        'report-uri' => null
    ],

    /**
     * Content Security Policy
     * References: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#csp
     * 
     * str-csp lets you define a one line string policy. Disabled if empty string otherwise csp is ignored.
     * Example: "script-src 'self' 'nonce-SomeRandomNonce'"
     */
    'str-csp' => '',

    'csp' => [
        'report-only' => false,
        'report-uri' => null, // csp violation reporting endpoint
        'upgrade-insecure-requests' => false,
        'base-uri' => [], // 'none'
        'default-src' => [
            'self' => true
        ], //'none'
        'child-src' => [
            'allow' => [
                // 'https://www.youtube.com'
            ],
            'self' => true
        ],
        'script-src' => [
            'add-nonces' => true,
            'allow' => [
                'https://www.google-analytics.com'                
            ],
            'hashes' => [
                // ['sha256' => 'hash-value'],
            ],
            'nonces' => [],
            'self' => true,
            'unsafe-inline' => false,
            'unsafe-eval' => true
        ],
        'style-src' => [
            'self' => true
        ],
        'img-src' => [
            'blob' => true,
            'self' => true,
            'data' => true
        ],
        'connect-src' => [],
        'font-src' => [
            'self' => true
        ],
        'form-action' => [
            'allow' => [
                // "https://example.com"
            ],
            'self' => true
        ],
        'frame-ancestors' => [],
        'media-src' => [],
        'object-src' => [],
        'plugin-types' => []      
    ]
];