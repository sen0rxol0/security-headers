<?php

use PHPUnit\Framework\TestCase;
// use Orchestra\Testbench\TestCase as Orchestra;
use Sen0rxol0\SecurityHeaders\SecurityHeadersBuilder;
use Illuminate\Container\Container;


final class SecurityHeadersBuilderTest extends TestCase {

  /**
   * @var string
   */
  protected $configPath = __DIR__ . '/../config/headers.php';

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

    $sh = new SecurityHeadersBuilder($config, Container::getInstance());

    return $sh;
  }

  /**
   * @covers SecurityHeadersBuilder
   */
  public function testNoSyntaxError(): void
  {
    $sh = $this->getBuilder();

    $this->assertTrue(is_object($sh));
    unset($sh);
  }

  /**
   * @covers SecurityHeadersBuilder::policies
   */
  public function testPolicies(): void
  {
    $policies = $this->getBuilder()->policies();

    $this->assertTrue(is_array($policies));

    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::getNonces
   */
  public function testNonces(): void
  {
    $config = $this->getConfig();

    $sh = new SecurityHeadersBuilder($config, Container::getInstance());

    $policies = $sh->policies();
    $nonces = $sh->getNonces();

    $this->assertTrue(is_array($nonces));
    $this->assertArrayHasKey('script', $nonces);
    $this->assertTrue(is_array($nonces['script']));

    unset($policies);
    unset($nonces);
  }

  /**
   * @covers SecurityHeadersBuilder::ecp
   * @covers SecurityHeadersBuilder::hsts
   */
  public function testEnabledPolicy(): void
  {
      $config = $this->getConfig();
      $config['ect']['enabled'] = true;
      $config['hsts']['enabled'] = true;

      $policies = $this->getBuilder($config)->policies();
      
      $this->assertArrayHasKey('Expect-CT', $policies);
      $this->assertArrayHasKey('Strict-Transport-Security', $policies);

      unset($config);
      unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::ecp
   * @covers SecurityHeadersBuilder::hsts
   */
  public function testDisabledPolicy(): void
  {
      $config = $this->getConfig();
      $config['ect']['enabled'] = false;
      $config['hsts']['enabled'] = false;      

      $policies = $this->getBuilder($config)->policies();
      
      $this->assertArrayNotHasKey('Expect-CT', $policies);
      $this->assertArrayNotHasKey('Strict-Transport-Security', $policies);   

      unset($config);
      unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::hsts
   */
  public function testHsts(): void
  {
    $config = $this->getConfig();
    $config['hsts']['enabled'] = true;
    $config['hsts']['preload'] = true;

    $policies = $this->getBuilder($config)->policies();

    $this->assertContains('preload', $policies['Strict-Transport-Security']);
    $this->assertContains('includeSubDomains', $policies['Strict-Transport-Security']);

    $policy = "max-age=31536000; includeSubDomains; preload";

    $this->assertArraySubset([
      'Strict-Transport-Security' => $policy
    ], $policies);

    unset($config);
    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::hpkp
   */
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

    $policies = $this->getBuilder($config)->policies();

    $this->assertArrayHasKey('Public-Key-Pins', $policies);

    $hpkp = "pin-sha256=\"cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs=\"; " .
            "max-age=5184000; includeSubDomains";
    
    $this->assertSame($hpkp, $policies['Public-Key-Pins']);

    unset($config);
    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::hpkp
   */
  public function testHpkpWithoutHash(): void
  {
    $policies = $this->getBuilder()->policies();

    $this->assertArrayNotHasKey('Public-Key-Pins', $policies);

    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::hpkp
   */
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

    $policies = $this->getBuilder($config)->policies();

    $this->assertArrayHasKey('Public-Key-Pins', $policies);   

    unset($config);
    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::hpkp
   */
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

    $policies = $this->getBuilder($config)->policies();
    
    $this->assertArrayNotHasKey('Public-Key-Pins-Report-Only', $policies); 
    $this->assertArrayHasKey('Public-Key-Pins', $policies); 
  
    $config['hpkp']['report-uri'] = 'https://report-uri.io/reportOnly';
    $policies = $this->getBuilder($config)->policies();

    $this->assertArrayHasKey('Public-Key-Pins-Report-Only', $policies);
      
    unset($config);
    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::csp
   */
  public function testCsp(): void 
  {
    $policies = $this->getBuilder()->policies();

    $this->assertArrayHasKey('Content-Security-Policy', $policies);
    $this->assertTrue(is_string($policies['Content-Security-Policy']));
    // $this->assertContains('script-src \'nonce', $policies['Content-Security-Policy']);

    unset($policies);
  }

  /**
   * @covers SecurityHeadersBuilder::csp
   */
  public function testCspWithStrcsp(): void
  {
    $config = $this->getConfig();
    $config['str-csp'] = "script-src 'self' 'nonce-SomeRandomNonce'";

    $policies = $this->getBuilder($config)->policies();

    $this->assertTrue($policies['Content-Security-Policy'] === $config['str-csp']);

    unset($config);
    unset($policies);
  }
}