<?php

class Migration_Install_events_schema extends Migration
{
  function up() {
    $ci =& get_instance();
    $ci->db->query("CREATE TABLE `events` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `type` varchar(255) NOT NULL,
                      `object` varchar(255) NOT NULL DEFAULT 'site',
                      `data` text,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
  }
  function down() {
    $ci =& get_instance();
    $ci->db->query('DROP TABLE `events`');
  }
}
