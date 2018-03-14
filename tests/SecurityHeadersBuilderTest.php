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
}