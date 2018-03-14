<?php

use PHPUnit\Framework\TestCase;
use Sen0rxol0\SecurityHeaders\SecurityHeadersBuilder;


final class SecurityHeadersBuilderTest extends TestCase {

  /**
   * @var string
   */
  protected $configPath = __DIR__ . '/../config/security-headers.php';

  /**
   * @var array
   */
  protected $config = [];

  protected function getConfig(): array
  {
    if (empty($this->config)) {
      $config = require $this->configPath;
      $this->config = $config;
      return $config;      
    }

    return $this->config;
  }

  protected function getBuilder(array $config = []): SecurityHeadersBuilder
  {
    if (empty($config)) {
      $config = $this->getConfig();
    }

    $sh = new SecurityHeadersBuilder($config);

    return $sh;
  }

  /**
   * @covers 
   */

  public function testNoSyntaxError(): void
  {
    $sh = $this->getBuilder();

    $this->assertTrue(is_object($sh));
    unset($sh);
  }

  public function testHeadersIsArray(): void
  {
    $headers = $this->getBuilder()->headers();

    $this->assertTrue(is_array($headers));

    unset($headers);
  }

  public function testEnabledPolicy(): void
  {
      $config = $this->getConfig();
      $config['ect']['enabled'] = true;
      $config['hsts']['enabled'] = true;

      $headers = $this->getBuilder($config)->headers();
      
      $this->assertArrayHasKey('Expect-CT', $headers);
      $this->assertArrayHasKey('Strict-Transport-Security', $headers);

      unset($config);
      unset($headers);
  }


  public function testDisabledPolicy(): void
  {
      $config = $this->getConfig();
      $config['ect']['enabled'] = false;
      $config['hsts']['enabled'] = false;      

      $headers = $this->getBuilder($config)->headers();
      
      $this->assertArrayNotHasKey('Expect-CT', $headers);
      $this->assertArrayNotHasKey('Strict-Transport-Security', $headers);   

      unset($config);
      unset($headers);
  }

  public function testHsts(): void
  {
    $config = $this->getConfig();
    $config['hsts']['enabled'] = true;
    $config['hsts']['preload'] = true;

    $headers = $this->getBuilder($config)->headers();

    $this->assertContains('preload', $headers['Strict-Transport-Security']);
    $this->assertContains('includeSubDomains', $headers['Strict-Transport-Security']);

    $policy = "max-age=31536000; includeSubDomains; preload";

    $this->assertArraySubset([
      'Strict-Transport-Security' => $policy
    ], $headers);

    unset($config);
    unset($headers);
  }

  public function testHpkp(): void
  {
    $config = $this->getConfig();
    $config['hpkp']['hashes'] = [
      [
        'algo' => 'sha256',
        'hash' => 'cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs='
      ]
    ];

    $config['hpkp']['include-subdomains'] = true;

    $headers = $this->getBuilder($config)->headers();

    $this->assertArrayHasKey('Public-Key-Pins', $headers);

    $hpkp = "pin-sha256=\"cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs=\"; " .
            "max-age=5184000; includeSubDomains";
    
    $this->assertSame($hpkp, $headers['Public-Key-Pins']);

    unset($config);
    unset($headers);
  }

  public function testHpkpWithoutHash(): void
  {
    $headers = $this->getBuilder()->headers();

    $this->assertArrayNotHasKey('Public-Key-Pins', $headers);

    unset($headers);
  }

  public function testHpkpWithHash(): void
  {
    $config = $this->getConfig();
    $config['hpkp']['hashes'] = [
      [
        'algo' => 'sha256',
        'hash' => 'cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs='
      ],
      [
        'algo' => 'sha256',
        'hash' => 'M8HztCzM3elUxkcjR2S5P4hhyBNf6lHkmjAHKhpGPWE='
      ]
    ];

    $headers = $this->getBuilder($config)->headers();

    $this->assertArrayHasKey('Public-Key-Pins', $headers);   

    unset($config);
    unset($headers);
  }

  public function testHpkpReport(): void
  {
    $config = $this->getConfig();
    $config['hpkp']['report-only'] = true;
    $config['hpkp']['hashes'] = [
      [
        'algo' => 'sha256',
        'hash' => 'cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs='
      ],
      [
        'algo' => 'sha256',
        'hash' => 'M8HztCzM3elUxkcjR2S5P4hhyBNf6lHkmjAHKhpGPWE='
      ]
    ];

    $headers = $this->getBuilder($config)->headers();
    
    $this->assertArrayNotHasKey('Public-Key-Pins-Report-Only', $headers); 
    $this->assertArrayHasKey('Public-Key-Pins', $headers); 
  
    $config['hpkp']['report-uri'] = 'https://report-uri.io/reportOnly';
    $headers = $this->getBuilder($config)->headers();

    $this->assertArrayHasKey('Public-Key-Pins-Report-Only', $headers);
      
    unset($config);
    unset($headers);
  }

  public function testCsp(): void 
  {
    $headers = $this->getBuilder()->headers();

    $this->assertArrayHasKey('Content-Security-Policy', $headers);
    $this->assertTrue(is_string($headers['Content-Security-Policy']));

    unset($headers);
  }

  public function testCspWithStrcsp(): void
  {
    $config = $this->getConfig();
    $config['str-csp'] = "script-src 'self' 'nonce-SomeRandomNonce'";

    $headers = $this->getBuilder($config)->headers();

    $this->assertTrue($headers['Content-Security-Policy'] === $config['str-csp']);

    unset($config);
    unset($headers);
  }
}