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

  protected function getBuilder(): SecurityHeadersBuilder
  {
    $sh = new SecurityHeadersBuilder($this->getConfig());

    return $sh;
  }

  public function testNoSyntaxError() : void
  {
    $obj = $this->getBuilder();

    $this->assertTrue(is_object($obj));
    unset($obj);
  }
  
  // public function testHeadersOutput(): void 
  // {
  //   $sh = $this->getBuilder();

  //   // $this->assertTrue($var->method1("hey") == 'Hello World');
  //   unset($sh);
  // }
}