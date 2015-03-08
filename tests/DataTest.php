<?php
namespace HeroPress;

class DataTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->data = new Data('sqlite::memory:');
    $this->data->_loadSchema();
  }

  function testUpsertSelect() {
    $this->assertEquals($this->data->_upsert('foo',  'bar'), 201);
    $this->assertEquals($this->data->_select('foo'), 'bar');
    $this->assertEquals($this->data->_upsert('foo',  'baz'), 200);
    $this->assertEquals($this->data->_select('foo'), 'baz');
  }
}
