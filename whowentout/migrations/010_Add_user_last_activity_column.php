<?php

class Migration_Add_user_last_activity_column extends Migration
{
  function up() {
    $this->db->query('ALTER TABLE `users`  ADD COLUMN `last_ping` DATETIME NULL AFTER `registration_time`');
  }
  function down() {
    $this->db->query('ALTER TABLE `users`  DROP COLUMN `last_ping`');
  }
}
