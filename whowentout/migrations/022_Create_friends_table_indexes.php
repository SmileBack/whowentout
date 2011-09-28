<?php

class Migration_Create_friends_table_indexes extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `friends`
        ADD INDEX `user_id` (`user_id`),
        ADD INDEX `user_facebook_id` (`user_facebook_id`),
        ADD INDEX `friend_id` (`friend_id`),
        ADD INDEX `friend_facebook_id` (`friend_facebook_id`)");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `friends`
        DROP INDEX `user_id`,
        DROP INDEX `user_facebook_id`,
        DROP INDEX `friend_id`,
        DROP INDEX `friend_facebook_id`");
    }

}