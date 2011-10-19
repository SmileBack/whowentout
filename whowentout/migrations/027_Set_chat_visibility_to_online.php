<?php

class Migration_Set_chat_visibility_to_online extends Migration
{
    function up()
    {
        $this->db->query("UPDATE users SET visible_to = 'online'");
        $this->db->query("ALTER TABLE `users`  CHANGE COLUMN `visible_to` `visible_to` VARCHAR(255) NOT NULL DEFAULT 'online' AFTER `chatbar_state`");
    }

    function down()
    {
        $this->db->query("UPDATE users SET visible_to = 'everyone'");
        $this->db->query("ALTER TABLE `users`  CHANGE COLUMN `visible_to` `visible_to` VARCHAR(255) NOT NULL DEFAULT 'everyone' AFTER `chatbar_state`");
    }
}
