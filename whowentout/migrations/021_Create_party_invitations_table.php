<?php

class Migration_Create_party_invitations_table extends Migration
{
    function up()
    {
        $this->db->query("CREATE TABLE `party_invitations` (
                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `created_at` DATETIME NULL DEFAULT NULL,
                                `party_id` INT(10) UNSIGNED NOT NULL,
                                `sender_id` INT(10) UNSIGNED NOT NULL,
                                `receiver_id` INT(10) UNSIGNED NULL DEFAULT NULL,
                                `college_student_id` INT(10) UNSIGNED NULL DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                INDEX `party_id_fk` (`party_id`),
                                INDEX `sender_id_fk` (`sender_id`),
                                INDEX `receiver_id_fk` (`receiver_id`),
                                INDEX `college_student_id_fk` (`college_student_id`),
                                CONSTRAINT `sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
                                CONSTRAINT `receiver_id_fk` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
                                CONSTRAINT `college_student_id_fk` FOREIGN KEY (`college_student_id`) REFERENCES `college_students` (`id`),
                                CONSTRAINT `party_id_fk` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`)
                            ) ENGINE=InnoDB");
    }

    function down()
    {
        $this->db->query('DROP TABLE party_invitations');
    }
}