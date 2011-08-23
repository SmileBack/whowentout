<?php

class SectionElement extends Element
{
  
  function process(&$vars) {
    $vars['body'] = load_view('sections/' . $vars['id'], $vars);
  }
  
}
