<?php

class Presence
{

    private $ci;

    private $status_changed = FALSE;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function status_changed()
    {
        return $this->status_changed;
    }
    
    function is_online($user_id)
    {
        $recent_window = $this->db->from('windows')
                ->where('user_id', $user_id)
                ->order_by('online_since', 'desc')
                ->limit(1)
                ->get()->row();

        return !empty($recent_window);
    }

    function ping_online($user_id)
    {
        $was_online = $this->is_online($user_id);

        $token = $this->token();
        $this->db->insert('windows', array(
                                          'id' => $token,
                                          'user_id' => $user_id,
                                          'online_since' => current_time()->format('Y-m-d H:i:s'),
                                     ));

        $is_online = $this->is_online($user_id);

        $this->status_changed = ($is_online != $was_online);

        if ($this->status_changed) {
            raise_event('user_came_online', array(
                                                 'user' => user($user_id),
                                            ));
        }

        return $token;
    }

    function ping_offline($user_id, $token)
    {
        $was_online = $this->is_online($user_id);
        $this->db->delete('windows', array('user_id' => $user_id, 'id' => $token));
        $is_online = $this->is_online($user_id);

        $this->status_changed = ($is_online != $was_online);

        if ($this->status_changed) {
            raise_event('user_went_offline', array(
                                                 'user' => user($user_id),
                                            ));
        }
    }

    function ping_active($user_id, $token)
    {
        $this->db->where('id', $token);
        $this->db->update('windows', array(
                                          'active_since' => current_time()->format('Y-m-d H:i:s'),
                                     ));
    }

    function token()
    {
        return hash('sha256', uniqid(mt_rand(), true));
    }

    function __install()
    {
        $ci =& get_instance();
        $ci->db->query("CREATE TABLE `windows` (
                          `id` varchar(64) NOT NULL,
                          `user_id` int(10) unsigned DEFAULT NULL,
                          `online_since` datetime DEFAULT NULL,
                          `offline_since` datetime DEFAULT NULL,
                          `active_since` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `windows_user_id_fk` (`user_id`),
                          CONSTRAINT `windows_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
    }

    function __uninstall()
    {
        $ci =& get_instance();
        $ci->db->query("DROP TABLE `windows`");
    }

}
