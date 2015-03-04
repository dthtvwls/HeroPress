<?php
namespace HeroPress;

class App extends \Slim\Slim {
  public function __construct($dsn = null, $opts = []) {
    parent::__construct(array_merge(['view' => new Handlebars], $opts));

    header_remove();
    session_name('session');
    session_start();
  }

  public function redirectBack() {
    if (php_sapi_name() !== 'cli') {
      $referer = $this->request->headers->get('Referer');
      $this->redirect($referer ? $referer : '/');
    }
  }

  public function addParams($merge = []) {
    return array_merge([
      'logged-in'  => Security::isLoggedIn(),
      'csrf-token' => Security::csrfToken(),
      'year'       => (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y')
    ], $merge);
  }
}
