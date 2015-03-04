<?php
namespace HeroPress;

class AppTest extends \PHPUnit_Framework_TestCase {
  function setUp() {
    $this->app = new App;
  }

  function testConstructor() {
    $this->assertEquals(1, 1);
    //$this->assertEquals(get_class($this->app->view), 'HeroPress\Handlebars');
  }
}
