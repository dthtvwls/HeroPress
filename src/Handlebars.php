<?php
class Handlebars extends Slim\View {

  var $fileext, $partials = [];

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
      $this->partials = array_reduce(scandir($basedir), function ($carry, $item) use ($basedir) {
        $pathinfo = pathinfo($item);
        if ($pathinfo['extension'] === $this->fileext) {
          $carry[$pathinfo['filename']] = file_get_contents("$basedir/$item");
        }
        return $carry;
      });
    }

    // use template from partials or read from file
    $renderer = LightnCandy::prepare(LightnCandy::compile(
      array_key_exists($template, $this->partials) ? $this->partials[$template] : file_get_contents($template), [
      'flags'    => LightnCandy::FLAG_HANDLEBARS,
      'partials' => $this->partials
    ]));

    echo $renderer($this->data->all());
  }
}
