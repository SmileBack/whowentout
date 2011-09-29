<?php

class Migration_Create_events_channel_index extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `events`  ADD INDEX `channel` (`channel`)");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `events`  DROP INDEX `channel`");
    }
}
