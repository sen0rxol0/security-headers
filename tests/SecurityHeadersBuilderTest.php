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

  public function testNoSyntaxError() : void
  {
    $sh = $this->getBuilder();

    $this->assertTrue(is_object($sh));
    unset($sh);
  }
  
  // public function testHeadersOutput(): void 
  // {
  //   $sh = $this->getBuilder();

  //   // $this->assertTrue($var->method1("hey") == 'Hello World');
  //   unset($sh);
  // }



  public function testEnabledPolicy()
  {
      $config = $this->getConfig();
      $config['ecp']['enabled'] = true;

      $headers = $this->getBuilder($config)->headers();
      
      $this->assertArrayHasKey('Expect-CT', $headers);

      unset($config);
      unset($headers);
  }
}