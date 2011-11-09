<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_chat_messages_table extends Migration {
  
  public function up() {
    $this->db->query('CREATE TABLE `chat_messages` (
                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                        `sent_at` int(10) unsigned NOT NULL,
                        `sender_id` int(10) unsigned NOT NULL,
                        `receiver_id` int(10) unsigned NOT NULL,
                        `message` text NOT NULL,
                        PRIMARY KEY (`id`),
                        KEY `sender_id` (`sender_id`),
                        KEY `receiver_id` (`receiver_id`),
                        CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
                        CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1');
    
  }
  
  public function down() {
    $this->db->query('DROP TABLE `chat_messages`');
  }
  
}
