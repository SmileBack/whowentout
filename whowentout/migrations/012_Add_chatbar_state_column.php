<?php

class Migration_Add_chatbar_state_column extends Migration
{
  function up() {
    $this->db->query('ALTER TABLE `users`  ADD COLUMN `chatbar_state` TEXT NULL AFTER `pic_version`');
  }
  function down() {
    $this->db->query('ALTER TABLE `users`  DROP COLUMN `chatbar_state`');
  }
}
