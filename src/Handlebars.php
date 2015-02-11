<?php
class Handlebars extends Slim\View {

  var $fileext;

  function __construct($fileext = 'hbs') {
    parent::__construct();
    $this->fileext = $fileext;
  }

  function render($template, $data = null) {

    /*
     * Load partials from the Slim templates dir, if present.
     * I actually prefer this method now because, in addition
     * to avoiding LightnCandy's wonky file loading,
     * if template is relative, we can just get it from the partials
     */
    if ($basedir = $this->getTemplatesDirectory()) {
      $partials = array_reduce(scandir($basedir), function ($carry, $item) use ($basedir) {
        $pathinfo = pathinfo($item);
        if ($pathinfo['extension'] === $this->fileext) {
          $carry[$pathinfo['filename']] = file_get_contents("$basedir/$item");
        }
        return $carry;
      });
    } else {
      $partials = [];
    }

    // use template from partials or read from file
    $renderer = eval('?>' . LightnCandy::compile(
      array_key_exists($template, $partials) ? $partials[$template] : file_get_contents($template), [
        'flags'    => LightnCandy::FLAG_HANDLEBARS,
        'partials' => $partials,
        'helpers'  => ['editable' => 'Handlebars::editable']
      ]
    ));

    echo $renderer($this->data->all());
  }

  static function editable($args) {
    $app = HeroPress::getInstance();

    if (!isset($args[1])) $args[1] = $app->select($args[0]);
    if (!isset($args[2])) $args[2] = $app->isLoggedIn();

    return "<div data-slug=\"$args[0]\"" . ($args[2] ? ' contenteditable="true"' : '') . ">$args[1]</div>";
  }
}
