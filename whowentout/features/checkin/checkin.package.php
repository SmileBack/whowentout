<?php

class CheckinPackage extends Package
{

    public $version = '0.2.2';

    function install()
    {
        if ($this->database->has_table('checkins'))
            return;

        $this->database->create_table('checkins', array(
            'id' => array('type' => 'id'),
            'user_id' => array('type' => 'integer'),
            'event_id' => array('type' => 'integer'),
            'time' => array('type' => 'time'),
        ));

        $this->database->table('checkins')->create_foreign_key('user_id', 'users', 'id');
        $this->database->table('checkins')->create_foreign_key('event_id', 'events', 'id');
        $this->database->table('checkins')->create_unique_index('user_id', 'event_id');

        $this->create_auto_update_count_triggers();
    }

    function update_0_2_2()
    {
        $this->create_auto_update_count_triggers();
    }

    function create_auto_update_count_triggers()
    {
        $this->database->execute('CREATE TRIGGER update_event_count_after_checkin AFTER INSERT ON checkins
          FOR EACH ROW
            UPDATE events
                SET count = (SELECT COUNT(*) FROM checkins WHERE checkins.event_id = events.id)
                WHERE id = NEW.event_id;');

        $this->database->execute('CREATE TRIGGER update_event_count_before_switch BEFORE DELETE ON checkins
          FOR EACH ROW
            UPDATE events
                SET count = (SELECT (COUNT(*)-1) AS count FROM checkins WHERE checkins.event_id = events.id)
                WHERE id = OLD.event_id;');
    }

    function uninstall()
    {
        $this->database->destroy_table('checkins');
    }

}
