<?php

namespace Sen0rxol0\SecurityHeaders;

use ParagonIE\CSPBuilder\CSPBuilder;
use ParagonIE\HPKPBuilder\HPKPBuilder;


/**
*  Class SecurityHeadersBuilder
*
*  Build security headers to enhance HTTP responses headers
*
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
     * Merges headers then stores it in the protected $headers property.
     *
     * @return array
     */
    protected function headers(): array
    {
        if (!empty($this->policies)) {
            return $this->policies;
        }

        $headers = array_merge(
            $this->hsts(),
            $this->csp(),
            $this->hpkp(),
            $this->getOtherPolicies()
        );

        $this->policies = $headers;

        return $headers;
    }

    /**
     * Get an associative array of Strict-Transport-Security header.
     *
     * @return array
     */
    protected function hsts(): array
    {
        if (!$this->config['hsts']['enabled']) {
            return [];
        }

        $hsts = "max-age={$this->config['hsts']['max-age']}";

        if ($this->config['hsts']['include-sub-domains']) {
            $hsts .= '; includeSubDomains';
        }
        // $hsts .= ' preload';

        return [
            'Strict-Transport-Security' => $hsts
        ];
    }


    /**
     * Get an associative array of Content-Security-Policy headers.
     * 
     * @return array
     */
    protected function csp() : array
    {
        if (!empty($this->config['str-csp'])) {
            return [
                'Content-Security-Policy' => $this->config['str-csp'],
            ];
        }

        if (empty($this->config['csp'])) {
            // throw new Error('Could not parse configuration!');
        }

        $this->$config['csp'] = $this->nonce(
            $this->config['csp'], 
            'script-src'
        );

        $this->$config['csp'] = $this->nonce(
            $this->config['csp'], 
            'style-src'
        );

        $csp = CSPBuilder::fromArray($this->config['csp']);
        // $csp->sendCSPHeader();

        return $csp->getHeaderArray();
    }


    /**
     * Create HPKP header.
     *
     * @return array
     */
    protected function hpkp()
    {
        if (!$this->config['hpkp']['enabled']) {
            return '';
        }

        if (empty($this->config['hpkp']['hashes'])) {
            $hash = $this->hash();

            $this->config['hpkp']['hashes'][] =  [
                'algo' => 'sha256',
                'hash' => $hash
            ];
        }


        // $header = ($reportOnly && !empty($reportUri))
        // ? 'Public-Key-Pins-Report-Only: '
        // : 'Public-Key-Pins: ';

        $hpkp = new HPKPBuilder($this->config['hpkp']);

        // return [
        //     'Strict-Transport-Security' => $hsts,
        // ];

        return $hpkp->getHeader();
    }

    /**
     * Get an associative array of headers.
     *
     * @return array
     */
    protected function getOtherPolicies(): array
    {
        return array_filter([
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
        ]);
    }

    /**
     * Adds a new nonce to policy directive. Returns the policy data.
     *
     * @param array $policy
     * @param string $directive
     * @return array
     */
    public function nonce(array $policy = [], string $directive = 'script-src'): array
    {
        $policyKeys = array_keys($policy);

        if (!in_array($directive, $policyKeys)) {
            return '';
        }

        $nonce = base64_encode(random_bytes(18));

        $policy[$directive]['nonces'][] = $nonce;

        return $policy;
    }


    /**
     * Generates a new hash for algo
     *
     * @param string $algo
     * @param string $script
     * @return string
     */
    protected function hash(string $algo = 'sha256', string $script = ''): string 
    {
        $hash = \hash($algo, $script);

        // bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

        return $hash;
    }
}
