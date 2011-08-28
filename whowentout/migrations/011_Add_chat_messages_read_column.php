<?php

class Migration_Add_chat_messages_read_column extends Migration
{
  function up() {
    $this->db->query("ALTER TABLE `chat_messages`  ADD COLUMN `is_read` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `message`");
  }
  function down() {
    $this->db->query('ALTER TABLE `chat_messages`  DROP COLUMN `is_read`');
  }
}