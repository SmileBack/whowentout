<?php

class SectionElement extends Element
{
  
  function process(&$vars) {
    $vars['body'] = load_view('sections/' . $vars['section_name'], $vars);
  }
  
}
