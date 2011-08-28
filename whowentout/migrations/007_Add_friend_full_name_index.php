<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_friend_full_name_index extends Migration {
  
  public function up() {
    $this->db->query('ALTER TABLE `friends`  ADD INDEX `friend_full_name` (`friend_full_name`)');
  }
  
  public function down() {
    $this->db->query('ALTER TABLE `friends`  DROP INDEX `friend_full_name`');
  }
  
}
