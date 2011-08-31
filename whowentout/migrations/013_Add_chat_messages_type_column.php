<?php

class Migration_Add_chat_messages_type_column extends Migration
{
  function up() {
    $this->db->query("ALTER TABLE `chat_messages`  ADD COLUMN `type` VARCHAR(255) NOT NULL DEFAULT 'normal' AFTER `id`");
  }
  function down() {
    $this->db->query('ALTER TABLE `chat_messages`  DROP COLUMN `type`');
  }
}
