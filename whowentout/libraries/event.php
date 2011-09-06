<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Event
{
  
  private $ci;
  private $db;
  
  private $plugins_loaded = FALSE;
  private $plugins = array();
  
  function __construct() {
    $this->ci =& get_instance();
    $this->ci->load->helper('event');
    $this->db = $this->ci->db;
  }
  
  function raise($event_name, $data = array()) {
    $data = (object)$data;
    foreach ($this->plugins() as $plugin_name => $plugin) {
      $handler = "on_$event_name";
      if (method_exists($plugin, $handler)) {
        $plugin->$handler($data);
      }
    }
  }
  
  function store($event_name, $data = array()) {
    $this->db->insert('events', array(
      'event' => $event_name,
      'data' => serialize($data),
      'source' => isset($data['source']) ? $data['source'] : 'site',
    ));
  }
  
  function plugins() {
    $this->load_plugins();
    return $this->plugins;
  }
  
  function plugin($name) {
    $this->load_plugins();
    return $this->plugins[$name];
  }
  
  private function load_plugins() {
    if ($this->plugins_loaded)
      return;
    
    foreach ($this->get_plugin_filepaths() as $plugin_name => $plugin_filepath) {
      require_once $plugin_filepath;
      $plugin_class = $plugin_name . 'plugin';
      $this->plugins[$plugin_name] = new $plugin_class;
    }
    
    $this->plugins_loaded = TRUE;
  }
  
  private function get_plugin_filepaths() {
    $paths = array();
    
    $files = files(APPPATH . 'plugins');
    foreach ($files as $filepath) {
      if (string_ends_with('.php', $filepath)) {
        $plugin_name = string_before_last('plugin.php', basename($filepath));
        $paths[$plugin_name] = $filepath;
      }
    }
    
    return $paths;
  }
  
}
