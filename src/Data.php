<?php
namespace HeroPress;

class Data extends \PDO {
  private static $default_dsn = '/../db/db.sqlite3';
  private static $schema_path = '/../db/schema.sql';

  use Facade;

  public function __construct($dsn = null) {
    parent::__construct(is_null($dsn) ? static::getdsn() : $dsn);
  }

  private static function getdsn() {
    if ($database_url = getenv('DATABASE_URL')) {
      $url = parse_url($database_url);
      $dsn = "pgsql:user={$url['user']};password={$url['pass']};host={$url['host']};dbname=" . ltrim($url['path'], '/');
    } else {
      $dsn = 'sqlite:' . __DIR__ . static::$default_dsn;
    }
    return $dsn;
  }

  public function _loadSchema($schema = null) {
    $this->exec(is_null($schema) ? file_get_contents(__DIR__ . static::$schema_path) : $schema);
  }

  public function _upsert($slug, $content = null) {
    if (is_null($content)) $content = App::getInstance()->request->getBody();

    $params = [ ':slug' => Security::purify($slug), ':content' => Security::purify($content) ];

    if ($this->prepare('INSERT INTO content (slug, content) VALUES (:slug, :content)')->execute($params)) {
      return 201;
    } else if ($this->prepare('UPDATE content SET content = :content WHERE slug = :slug')->execute($params)) {
      return 200;
    } else {
      return 500;
    }
  }

  public function _select($slug) {
    $sth = $this->prepare('SELECT content FROM content WHERE slug = :slug');
    $sth->execute([ ':slug' => $slug ]);
    return $sth->fetchColumn();
  }
}
