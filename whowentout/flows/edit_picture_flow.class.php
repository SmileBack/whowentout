<?php

class EditPictureFlow extends PageFlow
{

    function __construct()
    {
    }

    public function current()
    {
        return $this->current_state;
    }

    public function get_next()
    {
        $state = $this->current();

        if ($state == EditPictureFlow::START)
            return EditPictureFlow::END;
        else
            return null;
    }

    protected function execute_end()
    {
        redirect('profile/view/me');
    }

    private function get_event()
    {
        $event = db()->table('events')->row($this->event_id);
        return $event;
    }

}
