<?php
class HandlebarsView extends Slim\View {
  function render($template, $data = null) {
    $renderer = LightnCandy::prepare(LightnCandy::compile(file_get_contents($template), ['flags' => LightnCandy::FLAG_HANDLEBARSJS]));
    echo $renderer($this->data->all());
  }
}
