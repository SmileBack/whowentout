<?php

class Migration_Add_user_visibility_column extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `users`  ADD COLUMN `visible_to` VARCHAR(255) NOT NULL DEFAULT 'everyone' AFTER `chatbar_state`");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `users`  DROP COLUMN `visible_to`");
    }
}
