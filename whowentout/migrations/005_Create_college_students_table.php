<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_college_students_table extends Migration {
  
  public function up() {
    $this->db->query('CREATE TABLE `college_students` (
                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                        `college_id` int(10) unsigned NOT NULL,
                        `student_full_name` varchar(255) NOT NULL,
                        `student_email` varchar(255) NOT NULL,
                        PRIMARY KEY (`id`),
                        KEY `college_id` (`college_id`),
                        CONSTRAINT `college_students_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1');
  }
  
  public function down() {
    $this->db->query('DROP TABLE `college_students`');
  }
  
}
