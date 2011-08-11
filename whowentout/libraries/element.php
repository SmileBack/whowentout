<?php

class Element
{
  protected $vars = array();
  
  function __set($prop, $val) {
    $this->set($prop, $val);
  }
  
  function &__get($prop) {
    return $this->vars[$prop];
  }
  
  function set($prop, $val = NULL) {
    if (func_num_args() == 2) {
      $this->vars[$prop] = $val;
    }
    else {
      foreach ($prop as $k => $v) {
        $this->set($prop, $val);
      }
    }
  }
  
  function render() {
    if (method_exists($this, 'process'))
      $this->process($this->vars);
    
    extract($this->vars, EXTR_OVERWRITE);
    ob_start();
    include($this->template_path());             
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }
  
  function show() {
    print $this->render();
  }
  
  function template_path() {
    $name = $this->name();
    return APPPATH . "elements/$name/{$name}element.tpl.php";
  }
  
  function name() {
    $class = strtolower(get_class($this));
    return preg_replace('/element/', '', $class);
  }
  
  public function __toString() {
    return $this->render();
  }
  
}

/**
 * @param string $element_name 
 * @return Element
 */
function get_element($element_name) {
  require_once APPPATH . "elements/$element_name/{$element_name}element.class.php";
  $class = "{$element_name}element";
  return new $class;
}

function render($element_name, $vars = array()) {
  $element = get_element($element_name);
  foreach ($vars as $k => $v) {
    $element->set($k, $v);
  }
  return $element->render();
}
