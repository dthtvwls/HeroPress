<?php
namespace HeroPress;

class Data extends \PDO {
  private static $default_dsn = '/../db/db.sqlite3';
  private static $schema_path = '/../db/schema.sql';

  use Singleton;

  public function __construct($dsn = null) {
    parent::__construct(is_null($dsn) ? static::getdsn() : $dsn);
  }

  public static function getdsn() {
    if ($database_url = getenv('DATABASE_URL')) {
      $p = parse_url($database_url);
      $dsn = "pgsql:user={$p['user']};password={$p['pass']};host={$p['host']};dbname=" . ltrim($p['path'], '/');
    } else {
      $dsn = 'sqlite:' . __DIR__ . static::$default_dsn;
    }
    return $dsn;
  }

  public static function loadSchema($schema = null) {
    static::getInstance()->exec(is_null($schema) ? file_get_contents(__DIR__ . static::$schema_path) : $schema);
  }

  public static function upsert($slug, $content = null) {
    if (is_null($content)) $content = App::getInstance()->request->getBody();

    $params = [ ':slug' => Security::purify($slug), ':content' => Security::purify($content) ];

    if (static::getInstance()->prepare('INSERT INTO content (slug, content) VALUES (:slug, :content)')->execute($params)) {
      return 201;
    } else if (static::getInstance()->prepare('UPDATE content SET content = :content WHERE slug = :slug')->execute($params)) {
      return 200;
    } else {
      return 500;
    }
  }

  public static function select($slug) {
    $sth = static::getInstance()->prepare('SELECT content FROM content WHERE slug = :slug');
    $sth->execute([ ':slug' => $slug ]);
    return $sth->fetchColumn();
  }
}
