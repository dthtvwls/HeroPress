<?php
class HandlebarsTest extends PHPUnit_Framework_TestCase {
  function testRender() {

    $hv = new Handlebars;
    $hv->set('greeting', 'Hello World');

    ob_start();

    $hv->render('data:text/plain,<p>{{greeting}}</p>');
    $this->assertEquals(ob_get_contents(), '<p>Hello World</p>');

    ob_end_clean();
  }

  function testHelpers() {
    $this->assertEquals(Handlebars::editable(['foo', 'bar', false]), '<div data-slug="foo">bar</div>');
    $this->assertEquals(Handlebars::editable(['', '', true]), '<div data-slug="" contenteditable="true"></div>');
  }
}
