<?php

namespace Sen0rxol0\SecurityHeaders;

use ParagonIE\CSPBuilder\CSPBuilder;
use ParagonIE\HPKPBuilder\HPKPBuilder;


/**
*  Class SecurityHeadersBuilder
*
*  Build security headers to enhance HTTP responses headers
*
*  @package Sen0rxol0/SecurityHeaders
*  @author Walter Varela @sen0rxol0
*/
class SecurityHeadersBuilder {

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $nonces = [];

    /**
     * @var array
     */
    protected $policies = [];

    /**
     * Instanciate the builder
     * 
     * @param array $config Configuration data for building security headers
     */
    public function __construct(array $config) 
    {
        $this->config = $config;
    }

    /**
     * Merges header policies then stores it in the protected $policies property.
     * Retuns policies
     *
     * @return array
     */
    public function policies(): array
    {
        if (!empty($this->policies)) {
            return $this->policies;
        }

        $policies = array_merge(
            $this->hsts(),
            $this->csp(),
            $this->ect(),
            $this->hpkp(),
            $this->getCompiledPolicies()
        );

        $this->policies = $policies;

        return $policies;
    }

    /**
     * Retuns nonces
     *
     * @return array
     */
    public function getNonces(): array
    {
        return $this->nonces;
    }

    /**
     * Get an associative array of Strict-Transport-Security header.
     *
     * @return array
     */
    protected function hsts(): array
    {
        $enabled = $this->config['hsts']['enabled'];
        $preload = $this->config['hsts']['preload'];
        $maxAge = $this->config['hsts']['max-age'] ?? 31536000;
        $includeSubs = $this->config['hsts']['include-subdomains'];

        if (!$enabled) {
            return [];
        }

        $policy = "max-age={$maxAge}";

        if ($includeSubs) {
            $policy .= '; includeSubDomains';
        }

        if ($preload) {
            $policy .= '; preload';
        }

        return [
            'Strict-Transport-Security' => $policy
        ];
    }

    /**
     * Get an associative array of Expect-CT header
     * 
     * @return array
     */
    protected function ect(): array
    {
        $enabled = $this->config['ect']['enabled'];
        $maxAge = $this->config['ect']['max-age'] ?? 0;
        $enforce = $this->config['ect']['enforce'] ?? false;
        $reportUri = $this->config['ect']['report-uri'];

        if (!$enabled) {
            return [];
        }

        $policy = "max-age={$maxAge}";

        if ($enforce) {
            $policy .= '; enforce';
        }

        if (!empty($reportUri)) {
            $policy .= '; report-uri="' . $reportUri . '"';
        }

        return [
            'Expect-CT' => $policy
        ];
    }


    /**
     * Get an associative array of Content-Security-Policy headers.
     * 
     * @return array
     */
    protected function csp(): array
    {
        if (!empty($this->config['str-csp'])) {
            return [
                'Content-Security-Policy' => $this->config['str-csp'],
            ];
        }

        $scriptNonces = $this->config['csp']['script-src']['nonces'];

        if (empty($this->config['csp'])) {
            // throw new Error('Could not parse configuration!');
        }

        $csp = CSPBuilder::fromArray($this->config['csp']);

        if ($this->config['csp']['script-src']['add-nonces']) {
            if (!empty($scriptNonces)) {
                foreach ($scriptNonces as $nonce) {
                    $scriptNonce = $csp->nonce('script-src', $nonce);                    
                }              
            } else {
                $scriptNonces[] = $csp->nonce('script-src');
            }
        }

        // $nonce = $csp->nonce('style-src');

        $this->nonces = array_merge(
            $this->nonces,
            ['script' => $scriptNonces]
        );
        
        return $csp->getHeaderArray();
    }


    /**
     * Get an associative array HPKP header.
     *
     * @return array
     */
    protected function hpkp(): array
    {
        $policy = '';
        $hashes = $this->config['hpkp']['hashes'];        
        $maxAge = $this->config['hpkp']['max-age'] ?? 5184000;
        $includeSubs = $this->config['hpkp']['include-subdomains'] ?? false;
        $reportOnly = $this->config['hpkp']['report-only'] ?? false;
        $reportUri = $this->config['hpkp']['report-uri'] ?? null;

        if (empty($hashes)) {
            return [];
        }

        foreach ($hashes as $h) {
            $policy .= 'pin-' . $h['algo'] . '=';

            if (base64_encode(base64_decode($h['hash'], true)) === $h['hash']){
                $policy .= json_encode($h['hash']);
            } else {
                $policy .= json_encode(base64_encode($h['hash']));
            }

            $policy .= '; ';
        }

        $policy .= 'max-age=' . $maxAge;

        if ($includeSubs) {
            $policy .= '; includeSubDomains';
        }

        if ($reportUri) {
            $policy .= '; report-uri="' . $reportUri . '"';
        }

        $policyName = ($reportOnly && !empty($reportUri))
            ? 'Public-Key-Pins-Report-Only'
            : 'Public-Key-Pins';

        return [
           $policyName => $policy
        ];
    }

    /**
     * Get an associative array of headers.
     *
     * @return array
     */
    protected function getCompiledPolicies(): array
    {
        return array_filter([
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
        ]);
    }
}
