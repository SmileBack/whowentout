<?php

class Migration_Add_flickr_gallery_id_column extends Migration
{
    function up()
    {
        $this->db->query("ALTER TABLE `parties`  ADD COLUMN `flickr_gallery_id` VARCHAR(255) NULL AFTER `admin_id`");
    }

    function down()
    {
        $this->db->query("ALTER TABLE `parties`  DROP COLUMN `flickr_gallery_id`");
    }
}
