<?php
class HandlebarsTest extends PHPUnit_Framework_TestCase {
  function testCanRender() {

    $hv = new Handlebars;
    $hv->set('greeting', 'Hello World');

    ob_start();

    $hv->render('data:text/plain,<p>{{greeting}}</p>');
    $this->assertEquals(ob_get_contents(), '<p>Hello World</p>');

    ob_end_clean();
  }
}
