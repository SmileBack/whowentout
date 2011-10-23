<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Event
{

    private $ci;
    private $db;

    private $plugins_loaded = FALSE;
    private $plugins = array();
    private $is_enabled = TRUE;

    /**
     * @var The event that was most recently stored.
     */
    private $event_id;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper('event');
        $this->db = $this->ci->db;
    }

    function is_enabled()
    {
        return $this->is_enabled;
    }

    function enable()
    {
        $this->is_enabled = TRUE;
    }

    function disable()
    {
        $this->is_enabled = FALSE;
    }

    function raise($event_name, $event_data = array())
    {
        if (!$this->is_enabled())
            return;

        $e = $this->cast_event($event_name, $event_data);
        foreach ($this->plugins() as $plugin_name => $plugin) {
            $handler = "on_$event_name";
            if (method_exists($plugin, $handler)) {
                $plugin->$handler($e);
            }
        }
    }

    private function cast_event($event_name, $data = array())
    {
        $e = is_object($data) ? $data : (object)$data;
        $e->type = $event_name;
        $e->channel = isset($e->channel) ? $e->channel : 'site';
        return $e;
    }

    function store($event_name, $event_data = array())
    {
        $e = $this->cast_event($event_name, $event_data);
        $this->db->insert('events', array(
                                         'type' => $e->type,
                                         'channel' => $e->channel,
                                         'data' => json_encode($e),
                                    ));
    }

    function fetch($channel, $version)
    {
        $rows = $this->db->from('events')
                ->where('channel', $channel)
                ->where('id >', $version)
                ->get()->result();

        $events = array();
        foreach ($rows as $row) {
            $events[] = json_decode($row->data);
        }
        return $events;
    }

    function version($channel)
    {
        $row = $this->db->select_max('id')->from('events')
                ->where('channel', $channel)
                ->get()->row();
        return $row->id ? $row->id : 0;
    }

    function broadcast($channel, $event_name, $event_data = array())
    {
        $event_data['channel'] = $channel;
        $this->store($event_name, $event_data);
        serverchannel()->trigger($channel, $event_name, $event_data);
    }

    function plugins()
    {
        $this->load_plugins();
        return $this->plugins;
    }

    function plugin($name)
    {
        $this->load_plugins();
        return $this->plugins[$name];
    }

    private function load_plugins()
    {
        if ($this->plugins_loaded)
            return;

        $plugin_class_names = f()->class_loader()->get_subclass_names('Plugin');
        foreach ($plugin_class_names as $class_name) {
            $plugin_name = $this->string_before_last('plugin', strtolower($class_name));
            $this->plugins[$plugin_name] = f()->class_loader()->init($class_name);
        }
        
        $this->plugins_loaded = TRUE;
    }

    function string_before_last($needle, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, 0, $pos);
        }
    }

}
