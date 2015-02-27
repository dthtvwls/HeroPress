<?php
class HeroPress extends Slim\Slim {

  var $auth, $csrf, $dbh, $xss;

  public function __construct($dsn = null, $opts = []) {
    parent::__construct(array_merge(['view' => new Handlebars], $opts));

    header_remove();
    session_name('session');
    session_start();

    if ($dsn === null) {
      if (isset($_ENV['DATABASE_URL'])) {
        $url = parse_url($_ENV['DATABASE_URL']);
        $dsn = "pgsql:user={$url['user']};password={$url['pass']};host={$url['host']};dbname=" . ltrim($url['path'], '/');
      } else {
        $dsn = 'sqlite:' . __DIR__ . '/../db/db.sqlite3';
      }
    }

    $this->auth = new Aura\Auth\AuthFactory($_COOKIE);
    $this->csrf = (new Aura\Session\SessionFactory)->newInstance($_COOKIE)->getCsrfToken();
    $this->dbh  = new PDO($dsn);
    $this->xss  = new HTMLPurifier(HTMLPurifier_Config::createDefault());
  }

  public function dbLogin($input = null, $cols = ['username', 'password'], $from = 'users') {
    return function () use ($input, $cols, $from) {
      try {
        $this->auth->newLoginService($this->auth->newPdoAdapter(
          $this->dbh, new Aura\Auth\Verifier\PasswordVerifier(PASSWORD_BCRYPT), $cols, $from
        ))->login($this->auth->newInstance(), $input === null && isset($_POST) ? $_POST : $input);
      } catch (Exception $e) {
        // transform the exception's class name into sentence case
        preg_match('/\w+$/', get_class($e), $matches);
        $this->flash('error', ucfirst(strtolower(ltrim(preg_replace('/[A-Z]/', ' $0', $matches[0])))));
      }
      $this->redirectBack();
    };
  }

  public function logout() {
    return function () {
      $this->auth->newLogoutService()->logout($this->auth->newInstance());
      $this->redirectBack();
    };
  }

  public function redirectBack() {
    if (php_sapi_name() !== 'cli') {
      $referer = $this->request->headers->get('Referer');
      $this->redirect($referer ? $referer : '/');
    }
  }

  public function mergeParams($merge = []) {
    return array_merge([
      'logged-in'  => $this->isLoggedIn(),
      'csrf-token' => $this->csrfToken(),
      'year'       => (new DateTime('now', new DateTimeZone('UTC')))->format('Y')
    ], $merge);
  }

  public function isLoggedIn() {
    return $this->auth->newInstance()->isValid();
  }

  public function csrfToken() {
    return $this->csrf->getValue();
  }

  public function csrfValid($test = null) {
    if ($test === null) $test = $this->request->headers->get('X-CSRF-Token');
    return $this->csrf->isValid($test);
  }

  public function purify($string) {
    return $this->xss->purify($string);
  }

  public function upsert($slug, $content = null) {
    if ($content === null) $content = $this->request->getBody();

    $params = [ ':slug' => $this->purify($slug), ':content' => $this->purify($content) ];

    if ($this->dbh->prepare('INSERT INTO content (slug, content) VALUES (:slug, :content)')->execute($params)) {
      return 201;
    } else if ($this->dbh->prepare('UPDATE content SET content = :content WHERE slug = :slug')->execute($params)) {
      return 200;
    } else {
      return 500;
    }
  }

  public function select($slug) {
    $sth = $this->dbh->prepare('SELECT content FROM content WHERE slug = :slug');
    $sth->execute([':slug' => $slug]);
    return $sth->fetchColumn();
  }
}
