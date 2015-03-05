<?php
namespace HeroPress;

trait Singleton {
  private static $instance;

  public static function getInstance() {
    if (!static::$instance instanceof static) static::$instance = new static;
    return static::$instance;
  }

  /*public static function __callStatic($name, $arguments) {
    return call_user_func_array([static::getInstance(), $name], $arguments);
    //return call_user_func_array((new ReflectionMethod(static, $name))->getClosure()->bindTo(static::getInstance()), $arguments);
  }*/
}
