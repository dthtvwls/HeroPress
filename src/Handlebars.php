<?php
class Handlebars extends Slim\View {

  var $fileext;

  function __construct($fileext = 'hbs') {
    $this->fileext = $fileext;
    parent::__construct();
  }

  function render($template, $data = null) {

    $opts = [
      'flags'    => LightnCandy::FLAG_HANDLEBARS,
      //'basedir'  => $this->getTemplatesDirectory(),
      //'fileext'  => $this->fileext
    ];

    // I don't have the patience to figure out why LightnCandy basedir won't work right now
    // So we're doing this manually instead
    if ($basedir = $this->getTemplatesDirectory()) {
      $opts['partials'] = array_reduce(scandir($basedir), function ($carry, $item) use ($basedir) {
        $pathinfo = pathinfo("$basedir/$item");
        if ($pathinfo['extension'] === $this->fileext) {
          $carry[$pathinfo['filename']] = file_get_contents("$basedir/$item");
        }
        return $carry;
      });
    }

    // if template is a file we've already found in partials use that
    if (isset($opts['partials']) && array_key_exists($template, $opts['partials'])) {
      $template = $opts['partials'][$template];
    } else {
      $template = file_get_contents($template);
    }

    $renderer = LightnCandy::prepare(LightnCandy::compile($template, $opts));

    echo $renderer($this->data->all());
  }
}
