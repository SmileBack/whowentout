<?php

class Migration_Create_notifications_table extends Migration
{
    function up()
    {
        $this->db->query("CREATE TABLE `notifications` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `type` varchar(255) NOT NULL DEFAULT 'normal',
                              `sent_at` int(10) unsigned NOT NULL,
                              `user_id` int(10) unsigned NOT NULL,
                              `message` text NOT NULL,
                              `is_read` int(10) unsigned NOT NULL DEFAULT '0',
                              PRIMARY KEY (`id`),
                              KEY `user_id` (`user_id`),
                              CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                            ) ENGINE=InnoDB");
    }

    function down()
    {
        $this->db->query("DROP TABLE notifications");
    }
}
