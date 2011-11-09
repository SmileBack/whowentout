<?php

class Migration_Create_smile_matches_table extends Migration
{
    function up()
    {
        $this->db->query("CREATE TABLE `smile_matches` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `first_smile_id` int(10) unsigned NOT NULL,
                              `second_smile_id` int(10) unsigned NOT NULL,
                              `first_user_id` int(10) unsigned NOT NULL,
                              `second_user_id` int(10) unsigned NOT NULL,
                              `created_at` datetime NOT NULL,
                              PRIMARY KEY (`id`),
                              KEY `first_smile_id` (`first_smile_id`),
                              KEY `second_smile_id` (`second_smile_id`),
                              KEY `first_user_id` (`first_user_id`),
                              KEY `second_user_id` (`second_user_id`),
                              CONSTRAINT `first_smile_id_fk` FOREIGN KEY (`first_smile_id`) REFERENCES `smiles` (`id`),
                              CONSTRAINT `first_user_id_fk` FOREIGN KEY (`first_user_id`) REFERENCES `users` (`id`),
                              CONSTRAINT `second_smile_id_fk` FOREIGN KEY (`second_smile_id`) REFERENCES `smiles` (`id`),
                              CONSTRAINT `second_user_id_fk` FOREIGN KEY (`second_user_id`) REFERENCES `users` (`id`)
                            ) ENGINE=InnoDB");
    }

    function down()
    {
        $this->db->query("DROP TABLE `smile_matches`");
    }
}
