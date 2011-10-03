<?php

class Migration_Rename_idle_column extends Migration
{
    function up()
    {
        $this->db->query("UPDATE users SET idle_since = NULL");
        $this->db->query("ALTER TABLE `users`  CHANGE COLUMN `idle_since` `last_active` DATETIME NULL DEFAULT NULL AFTER `visible_to`");
    }

    function down()
    {
        $this->db->query("UPDATE users SET last_active = NULL");
        $this->db->query("ALTER TABLE `users`  CHANGE COLUMN `last_active` `idle_since` DATETIME NULL DEFAULT NULL AFTER `visible_to`");
    }
}
