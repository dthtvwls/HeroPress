<?php
class HeroPressTest extends PHPUnit_Framework_TestCase {
  static $app;

  static function setUpBeforeClass() {
    self::$app = new HeroPress('sqlite::memory:');
    self::$app->dbh->exec(file_get_contents(__DIR__ . '/../db/schema.sql'));
  }

  function testConstructor() {
    $this->assertEquals(get_class(self::$app->auth), 'Aura\Auth\AuthFactory');
    $this->assertEquals(get_class(self::$app->csrf), 'Aura\Session\CsrfToken');
    $this->assertEquals(get_class(self::$app->dbh),  'PDO');
    $this->assertEquals(get_class(self::$app->xss),  'HTMLPurifier');
    $this->assertEquals(get_class(self::$app->view), 'Handlebars');
  }

  function testCsrf() {
    $this->assertEquals(self::$app->csrfValid(self::$app->csrfToken()), true);
  }

  function testXSS() {
    $this->assertEquals(self::$app->purify('<img src="javascript:evil()" onload="evil()">'), '');
  }

  function testDBH() {
    $this->assertEquals(self::$app->upsert('foo',  'bar'), 201);
    $this->assertEquals(self::$app->select('foo'), 'bar');
    $this->assertEquals(self::$app->upsert('foo',  'baz'), 200);
    $this->assertEquals(self::$app->select('foo'), 'baz');
  }

  function testLoginLogout() {
    $this->assertEquals(self::$app->isLoggedIn(), false);

    // bcrypt really slows the test down but I don't want to put some crypted string in the test
    $password = password_hash("password", PASSWORD_BCRYPT);
    self::$app->dbh->exec("INSERT INTO users (username, password) VALUES ('username', '$password')");

    $login = self::$app->dbLogin(['username' => 'username', 'password' => 'password']);
    $login();

    $this->assertEquals(self::$app->isLoggedIn(), true);

    $logout = self::$app->logout();
    $logout();

    $this->assertEquals(self::$app->isLoggedIn(), false);
  }
}
