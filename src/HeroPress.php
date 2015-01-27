<?php
class HeroPress extends Slim\Slim {

  var $auth, $csrf, $dbh;

  function __construct($dsn, $opts = []) {
    parent::__construct(array_merge(['view' => new Handlebars], $opts));

    session_start();

    $this->auth = new Aura\Auth\AuthFactory($_COOKIE);
    $this->csrf = (new Aura\Session\SessionFactory)->newInstance($_COOKIE)->getCsrfToken();
    $this->dbh  = new PDO($dsn);
  }

  function databaseLoginHandler($input = null) {
    return function () use ($input) {
      try {
        $this->auth->newLoginService($this->auth->newPdoAdapter(
          $this->dbh, new Aura\Auth\Verifier\PasswordVerifier(PASSWORD_BCRYPT), ['username', 'password'], 'users'
        ))->login($this->auth->newInstance(), $input === null ? $_POST : $input);
      } catch (Exception $e) {
        // this fun function list just transforms the exception's class name into sentence case
        $this->flash('error', ucfirst(strtolower(ltrim(preg_replace('/[A-Z]/', ' $0', array_pop(explode('\\', get_class($e))))))));
      }
      $this->redirectBack();
    };
  }

  function genericLogoutHandler() {
    return function () {
      $this->auth->newLogoutService()->logout($this->auth->newInstance());
      $this->redirectBack();
    };
  }

  function redirectBack() {
    if (php_sapi_name() !== 'cli') $this->redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/');
  }

  function isLoggedIn() {
    return $this->auth->newInstance()->isValid();
  }

  function csrfToken() {
    return $this->csrf->getValue();
  }

  function csrfValid($test = null) {
    if ($test === null && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) $test = $_SERVER['HTTP_X_CSRF_TOKEN'];
    return $this->csrf->isValid($test);
  }

  function filterXSS($string) {
    return (new HTMLPurifier(HTMLPurifier_Config::createDefault()))->purify($string);
  }

  function upsert($slug, $content) {
    $params = [':slug' => $slug, ':content' => $this->filterXSS($content)];

    if ($this->dbh->prepare('INSERT INTO content (slug, content) VALUES (:slug, :content)')->execute($params)) {
      return 201;
    } else if ($this->dbh->prepare('UPDATE content SET content = :content WHERE slug = :slug')->execute($params)) {
      return 200;
    } else {
      return 500;
    }
  }

  function select($slug) {
    $sth = $this->dbh->prepare('SELECT content FROM content WHERE slug = :slug');
    $sth->execute([':slug' => $slug]);
    return $sth->fetchColumn();
  }
}
