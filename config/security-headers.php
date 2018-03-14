<?php

return [
    /*
     * X-Content-Type-Options
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xcto
     *
     * Possible values: 'nosniff'
     */
    'x-content-type-options' => 'nosniff',

    /*
     * X-Frame-Options
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xfo
     *
     * Posible values: 'deny', 'sameorigin', 'allow-from <domain>'
     */
    'x-frame-options' => 'deny',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xpcdp
     *
     * Possible values: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */
    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-XSS-Protection
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#xxxsp
     *
     * Possible values: '1', '0', '1; mode=block', '1; report=http://example.com/report_URI'
     */
    'x-xss-protection' => '1; mode=block',

    /*
     * Referrer-Policy
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#rp
     *
     * Possible values: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */
    'referrer-policy' => 'no-referrer',

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#hsts
     *
     * Ensure that your app is under https before enabling this.
     */
    'hsts' => [
        'enabled' => false,
        'max-age' => 31536000,
        'include-sub-domains' => true
    ],

    /*
     * HTTP Public Key Pinning
     *
     * Reference: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#hpkp
     *
     * hpkp will be ignored if hashes if not enabled .
     */
    'hpkp' => [
        'enabled' => false,
        // hash is required, generated if not profided when enabled
        'hashes' => [
            // [
            //     'algo' => 'sha256', // Browsers currently only support sha256 public key pins.
            //     'hash' => 'hash-value',
            // ],
        ],
        'include-sub-domains' => false,
        'max-age' => 5184000,
        'report-only' => false,
        'report-uri' => null
    ],

    /*
     * Content Security Policy
     *
     * References: https://www.owasp.org/index.php/OWASP_Secure_Headers_Project#csp
     *            
     * str-csp lets you define a one line string policy. Disabled if empty string otherwise csp is ignored.
     * 
     * Example: "script-src 'self' 'nonce-SomeRandomNonce'"
     * 
     */
    'str-csp' => '',

    'csp' => [
        'report-only' => false,
        'report-uri' => null, // csp violation reporting endpoint
        'upgrade-insecure-requests' => false,
        'base-uri' => [
            //
        ],

        'default-src' => 'none',

        'child-src' => [
            'allow' => [
                // 'https://www.youtube.com'
            ],
            'self' => false
        ],

        'script-src' => [
            'allow' => [
                'https://www.google-analytics.com'                
            ],
            'hashes' => [
                // ['sha256' => 'hash-value'],
            ],
            'nonces' => [
                //
            ],
            'self' => true,
            'unsafe-inline' => false,
            'unsafe-eval' => false,
            'add-generated-nonce' => false
        ],

        'style-src' => [
            'allow' => [
                //
            ],
            'nonces' => [
                //
            ],
            'self' => true,
            'unsafe-inline' => false,
            'add-generated-nonce' => false,
        ],

        'img-src' => [
            'allow' => [
                //
            ],
            'types' => [
                //
            ],
            'self' => true,
            'data' => true
        ],

        'connect-src' => [
            //
        ],

        'font-src' => [
            'self' => true
        ],

        'form-action' => [
            'allow' => [
                // "https://example.com"
            ],
            'self' => true
        ],

        'frame-ancestors' => [
            //
        ],

        'media-src' => [
            //
        ],

        'object-src' => [
            //
        ],

        'plugin-types' => [
            //
        ]      
    ]
];