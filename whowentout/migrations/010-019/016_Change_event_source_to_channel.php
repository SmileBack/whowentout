<?php

class Migration_Change_event_source_to_channel extends Migration
{

    function up()
    {
        $this->db->query("ALTER TABLE `events`  CHANGE COLUMN `source` `channel` VARCHAR(255) NOT NULL DEFAULT 'site' AFTER `type`");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `events`  CHANGE COLUMN `channel` `source` VARCHAR(255) NOT NULL DEFAULT 'site' AFTER `type`");
    }

}