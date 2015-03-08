<?php
namespace HeroPress;

class SecurityTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->security = new Security;
  }

  function testCsrf() {
    $this->assertEquals($this->security->_csrfValid($this->security->_csrfToken()), true);
  }

  function testXSS() {
    $this->assertEquals($this->security->_purify('<img src="javascript:evil()" onload="evil()">'), '');
  }

  function testLoginLogout() {

    // Prep a db object and user
    $data = new Data('sqlite::memory:');
    $data->_loadSchema();
    $data->exec("INSERT INTO users (username, password) VALUES ('username', '" . password_hash('password', PASSWORD_BCRYPT) . "')");
    Data::setInstance($data);


    $this->assertEquals($this->security->_isLoggedIn(), false);

    $login = $this->security->_login(['username' => 'username', 'password' => 'password']);
    $login();

    $this->assertEquals($this->security->_isLoggedIn(), true);

    $logout = $this->security->_logout();
    $logout();

    $this->assertEquals($this->security->_isLoggedIn(), false);
  }
}
