<?php

use PHPUnit\Framework\TestCase;
use Sen0rxol0\SecurityHeaders\SecurityHeadersBuilder;


final class SecurityHeadersBuilderTest extends TestCase {

  /**
   * @var string
   */
  protected $configPath = __DIR__ . '/../config/security-headers.php';

  public function testNoSyntaxError() : void
  {
    $config = require $this->configPath;

    $obj = new SecurityHeadersBuilder($config);
    $this->assertTrue(is_object($obj));
    unset($obj);
  }
  
  // public function testMethod1(){
  //   $var = new Buonzz\Template\YourClass;
  //   $this->assertTrue($var->method1("hey") == 'Hello World');
  //   unset($var);
  // }
  
}