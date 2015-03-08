<?php
namespace HeroPress;

class Security {

  use Facade;

  private $auth, $csrf, $xss;

  public function __construct() {
    $this->auth = new \Aura\Auth\AuthFactory($_COOKIE);
    $this->csrf = (new \Aura\Session\SessionFactory)->newInstance($_COOKIE)->getCsrfToken();
    $this->xss  = new \HTMLPurifier(\HTMLPurifier_Config::createDefault());
  }

  public function _login($input = null, $cols = ['username', 'password'], $from = 'users') {
    return function () use ($input, $cols, $from) {
      try {
        $this->auth->newLoginService($this->auth->newPdoAdapter(
          Data::getInstance(), new \Aura\Auth\Verifier\PasswordVerifier(PASSWORD_BCRYPT), $cols, $from
        ))->login($this->auth->newInstance(), is_null($input) && isset($_POST) ? $_POST : $input);
      } catch (\Exception $e) {
        // transform the exception's class name into sentence case
        preg_match('/\w+$/', get_class($e), $matches);
        App::getInstance()->flash('error', ucfirst(strtolower(ltrim(preg_replace('/[A-Z]/', ' $0', $matches[0])))));
      }
      App::getInstance()->redirectBack();
    };
  }

  public function _logout() {
    return function () {
      $this->auth->newLogoutService()->logout($this->auth->newInstance());
      App::getInstance()->redirectBack();
    };
  }

  public function _isLoggedIn() {
    return $this->auth->newInstance()->isValid();
  }

  public function _csrfToken() {
    return $this->csrf->getValue();
  }

  public function _csrfValid($test = null) {
    if (is_null($test)) $test = App::getInstance()->request->headers->get('X-CSRF-Token');
    return $this->csrf->isValid($test);
  }

  public function _purify($string) {
    return $this->xss->purify($string);
  }
}
