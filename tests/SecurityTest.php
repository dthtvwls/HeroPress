<?php
namespace HeroPress;

class SecurityTest extends \PHPUnit_Framework_TestCase {

  /*function testConstructor() {
    $this->assertInstanceOf('Aura\Auth\AuthFactory',  static::$auth);
    $this->assertInstanceOf('Aura\Session\CsrfToken', static::$csrf);
    $this->assertInstanceOf('HTMLPurifier',           static::$xss);
  }*/

  /*function testCsrf() {
    $this->assertEquals(Security::csrfValid(Security::csrfToken()), true);
  }*/

  function testXSS() {
    $this->assertEquals(Security::purify('<img src="javascript:evil()" onload="evil()">'), '');
  }

  /*function testLoginLogout() {
    $this->assertEquals(Security::isLoggedIn(), false);

    // bcrypt really slows the test down but I don't want to put some crypted string in the test
    $password = password_hash('password', PASSWORD_BCRYPT);
    Data::getInstance()->exec("INSERT INTO users (username, password) VALUES ('username', '$password')");

    $login = Security::login(['username' => 'username', 'password' => 'password']);
    $login();

    $this->assertEquals(Security::isLoggedIn(), true);

    $logout = Security::logout();
    $logout();

    $this->assertEquals(Security::isLoggedIn(), false);
  }*/
}
