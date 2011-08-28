<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_party_attendee_fk extends Migration {

  public function up() {
    $verbose = $this->migrations->is_verbose();
    
    $this->db->trans_start();
    $this->db->query('ALTER TABLE `party_attendees`  ADD CONSTRAINT `party_attendee_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)');
    $this->db->query('ALTER TABLE `party_attendees`  ADD CONSTRAINT `party_attendee_party` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`)');
    $this->db->trans_complete();
  }

  public function down() {
    $this->db->trans_start();
    $this->db->query('ALTER TABLE `party_attendees`  DROP FOREIGN KEY `party_attendee_user`');
    $this->db->query('ALTER TABLE `party_attendees`  DROP FOREIGN KEY `party_attendee_party`');
    $this->db->trans_complete();
  }
  
}
