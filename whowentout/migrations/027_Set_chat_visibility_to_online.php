<?php

class Migration_Set_chat_visibility_to_online extends Migration
{
    function up()
    {
        $this->db->query("UPDATE users SET visible_to = 'online'");
    }

    function down()
    {
        $this->db->query("UPDATE users SET visible_to = 'everyone'");
    }
}
