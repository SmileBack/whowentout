<?php

class Migration_Create_facebook_access_token_column extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `users`  ADD COLUMN `facebook_access_token` VARCHAR(512) NULL AFTER `facebook_id`");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `users`  DROP COLUMN `facebook_access_token`");
    }
}
