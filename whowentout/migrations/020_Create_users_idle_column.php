<?php

class Migration_Create_users_idle_column extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `users`  ADD COLUMN `idle_for` INT(10) UNSIGNED NULL AFTER `last_ping`");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `users`  DROP COLUMN `idle_for`");
    }
}
