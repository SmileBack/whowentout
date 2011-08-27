<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_party_attendee_counter extends Migration {

  public function up() {
    $verbose = $this->migrations->is_verbose();
    $this->db->trans_start();
    
    $this->db->query('SET foreign_key_checks = 0');
    $this->db->query('ALTER TABLE `party_attendees` DROP INDEX `party_id`');
    $this->db->query('ALTER TABLE `party_attendees` DROP FOREIGN KEY `party_attendees_user_id`');
    $this->db->query('ALTER TABLE `party_attendees` DROP PRIMARY KEY');
    $this->db->query('SET foreign_key_checks = 1');
    
    $this->db->query('ALTER TABLE `party_attendees` ADD UNIQUE INDEX `user_id_party_id` (`user_id`, `party_id`)');
    $this->db->query('ALTER TABLE `party_attendees`  ADD COLUMN `id` INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL');
    
    $this->db->trans_complete();
  }

  public function down() {
  }
  
}
