<?php
class HeroPressTest extends PHPUnit_Framework_TestCase {
  static $app;

  static function setUpBeforeClass() {
    self::$app = new HeroPress('sqlite::memory:');
    self::$app->dbh->query(file_get_contents(__DIR__ . '/../db/schema.sql'));
  }

  function testConstructor() {
    $this->assertEquals(get_class(self::$app->auth), 'Aura\Auth\AuthFactory');
    $this->assertEquals(get_class(self::$app->csrf), 'Aura\Session\CsrfToken');
    $this->assertEquals(get_class(self::$app->dbh),  'PDO');
    $this->assertEquals(get_class(self::$app->view), 'HandlebarsView');
  }

  function testCsrf() {
    $this->assertEquals(self::$app->csrfValid(self::$app->csrfToken()), true);
  }

  function testFilterXSS() {
    $this->assertEquals(self::$app->filterXSS('<img src="javascript:evil()" onload="evil()">'), '');
  }

  function testDBH() {
    $this->assertEquals(self::$app->upsert('foo', 'bar'), 201);
    $this->assertEquals(self::$app->upsert('foo', 'baz'), 200);
    $this->assertEquals(self::$app->select('foo'), 'baz');
  }

  function testLoginLogout() {
    $this->assertEquals(self::$app->isLoggedIn(), false);
    self::$app->dbh->query('INSERT INTO users (username, password) VALUES ("username", "password")');
    // unfinished
  }
}
