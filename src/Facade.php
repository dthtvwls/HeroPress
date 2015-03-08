<?php
namespace HeroPress;

trait Facade {
  private static $instance = null;

  public static function getInstance() {
    if (is_null(static::$instance)) static::setInstance(new static);
    return static::$instance;
  }

  public static function setInstance($instance) {
    return static::$instance = $instance;
  }

  public static function __callStatic($name, $arguments) {
    return call_user_func_array([static::getInstance(), "_$name"], $arguments);
  }
}
