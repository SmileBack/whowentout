<?php

class Migration_Add_facebook_id_column_to_college_students_table
{
  
  public function up() {
    $this->db->trans_start();
    $this->db->query('ALTER TABLE `college_students`  ADD COLUMN `facebook_id` VARCHAR(255) NOT NULL AFTER `college_id`');
    $this->db->query('ALTER TABLE `college_students`  ADD INDEX `facebook_id` (`facebook_id`)');
    $this->db->trans_complete();
    
  }
  
  public function down() {
    $this->db->trans_start();
    $this->db->query('ALTER TABLE `college_students`  DROP INDEX `facebook_id`');
    $this->db->query('ALTER TABLE `college_students`  DROP COLUMN `facebook_id`');
    $this->db->trans_complete();
  }
  
}
