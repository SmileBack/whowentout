<?php

class Migration_Create_user_log_table extends Migration
{
    function up()
    {
        $this->db->query("CREATE TABLE `user_log` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user_id` int(10) unsigned NOT NULL,
                              `action` varchar(255) NOT NULL,
                              `time` datetime DEFAULT NULL,
                              `data` text,
                              PRIMARY KEY (`id`),
                              KEY `user_log_user_id_fk` (`user_id`),
                              CONSTRAINT `user_log_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                            ) ENGINE=InnoDB");
    }

    function down()
    {
        $this->db->query("DROP TABLE `user_log`");
    }
}
