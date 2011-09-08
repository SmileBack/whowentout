<?php

class Migration_Create_sent_emails_table extends Migration
{

    function up()
    {
        $ci =& get_instance();
        $ci->db->query("CREATE TABLE `sent_emails` (
                            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                            `recipient_email` varchar(255) NOT NULL,
                            `subject` text,
                            `body` text,
                            PRIMARY KEY (`id`)
                          ) ENGINE=InnoDB");
    }

    function down()
    {
        $ci =& get_instance();
        $ci->db->query("DROP TABLE `sent_emails`");
    }

}
