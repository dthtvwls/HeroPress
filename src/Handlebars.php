<?php
namespace HeroPress;

class Handlebars extends \Slim\View {

  private $fileext;

  public function __construct($fileext = 'hbs') {
    parent::__construct();
    $this->fileext = $fileext;
  }

  public function render($template, $data = null) {

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
    // unconvinced that tmpfile is better than eval
    $tmpfile = stream_get_meta_data(tmpfile())['uri'];
    file_put_contents($tmpfile, \LightnCandy::compile(
      array_key_exists($template, $partials) ? $partials[$template] : file_get_contents($template), [
        'flags'    => \LightnCandy::FLAG_HANDLEBARS,
        'partials' => $partials,
        'helpers'  => ['editable' => '\HeroPress\Handlebars::editable']
      ]
    ));
    $renderer = include($tmpfile);

    echo $renderer($this->data->all());
  }

  public static function editable($args) {
    if (!isset($args[0])) $args[0] = '';
    if (!isset($args[1])) $args[1] = \HeroPress\Data::select($args[0]);
    if (!isset($args[2])) $args[2] = \HeroPress\Security::isLoggedIn();

    return "<div data-slug=\"$args[0]\"" . ($args[2] ? ' contenteditable="true"' : '') . ">$args[1]</div>";
  }
}
