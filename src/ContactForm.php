<?php
namespace HeroPress;

class ContactForm {
  public static function form($action) {
    return <<<EOD
    <form method="post" action="$action">
      <div class="form-group">
        <input type="text" class="form-control" name="name" placeholder="Name" required>
      </div>
      <div class="form-group">
        <input type="tel" class="form-control" name="phone" placeholder="Phone" required>
      </div>
      <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
EOD;
  }

  public static function handler($input = null) {
    if (is_null($input) && isset($_POST)) $input = $_POST;
    return function () use ($input) {
      if ($username = getenv('SENDGRID_USERNAME') && $password = getenv('SENDGRID_PASSWORD')) {
        (new \SendGrid($username, $password))->send(
          (new \SendGrid\Email())->
          addTo('josh.stauter@gmail.com')->
          setFrom($input['email'])->
          setSubject('Website Contact')->
          setText(implode("\n", $input))
        );
      }
      App::getInstance()->redirectBack();
    };
  }
}
