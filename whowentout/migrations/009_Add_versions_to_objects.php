<?php

class Migration_Add_versions_to_objects extends Migration {
  
  public function up() {
    $this->db->query("ALTER TABLE `parties`  ADD COLUMN `version` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`");
    $this->db->query("ALTER TABLE `users`  ADD COLUMN `version` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`");
  }
  
  public function down() {
    $this->db->query('ALTER TABLE `parties`  DROP COLUMN `version`');
    $this->db->query('ALTER TABLE `users`  DROP COLUMN `version`');
  }
  
}
