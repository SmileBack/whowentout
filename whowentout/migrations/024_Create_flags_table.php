<?php

class Migration_Create_flags_table extends Migration
{

    function up()
    {
        $this->db->query("CREATE TABLE `flags` (
                          `id` varchar(512) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB");
    }

    function down()
    {
        $this->db->query("DROP TABLE flags");
    }

}
