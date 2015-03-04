<?php
namespace HeroPress;

class Security {

  use Singleton;

  private $auth, $csrf, $xss;

  public function __construct() {
    $this->auth = new \Aura\Auth\AuthFactory($_COOKIE);
    $this->csrf = (new \Aura\Session\SessionFactory)->newInstance($_COOKIE)->getCsrfToken();
    $this->xss  = new \HTMLPurifier(\HTMLPurifier_Config::createDefault());
  }

  public static function login($input = null, $cols = ['username', 'password'], $from = 'users') {
    return function () use ($input, $cols, $from) {
      try {
        static::getInstance()->auth->newLoginService(static::getInstance()->auth->newPdoAdapter(
          Data::getInstance(), new \Aura\Auth\Verifier\PasswordVerifier(PASSWORD_BCRYPT), $cols, $from
        ))->login(static::getInstance()->auth->newInstance(), is_null($input) && isset($_POST) ? $_POST : $input);
      } catch (\Exception $e) {
        // transform the exception's class name into sentence case
        preg_match('/\w+$/', get_class($e), $matches);
        App::getInstance()->flash('error', ucfirst(strtolower(ltrim(preg_replace('/[A-Z]/', ' $0', $matches[0])))));
      }
      App::getInstance()->redirectBack();
    };
  }

  public static function logout() {
    return function () {
      static::getInstance()->auth->newLogoutService()->logout(static::getInstance()->auth->newInstance());
      App::getInstance()->redirectBack();
    };
  }

  public static function isLoggedIn() {
    return static::getInstance()->auth->newInstance()->isValid();
  }

  public static function csrfToken() {
    return static::getInstance()->csrf->getValue();
  }

  public static function csrfValid($test = null) {
    if (is_null($test)) $test = App::getInstance()->request->headers->get('X-CSRF-Token');
    return static::getInstance()->csrf->isValid($test);
  }

  public static function purify($string) {
    return static::getInstance()->xss->purify($string);
  }
}
