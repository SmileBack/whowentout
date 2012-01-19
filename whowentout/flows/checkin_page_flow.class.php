<?php


class CheckinPageFlow extends PageFlow
{

    public $has_sent_invite = false;
    public $event_id = null;

    const SHOW_DEAL = 'deal_dialog';
    const INVITE = 'invite';

    public function current()
    {
        return $this->current_state;
    }

    public function set_state($state)
    {
        $this->current_state = $state;
    }

    public function get_next()
    {
        $state = $this->current();

        if ($state == CheckinPageFlow::START) {
            return $this->after_start();
        }
        elseif ($state == CheckinPageFlow::SHOW_DEAL) {
            return $this->after_deal_dialog();
        }
        elseif ($state == CheckinPageFlow::INVITE) {
            return CheckinPageFlow::END;
        }

        return null;
    }

    protected function execute_deal_dialog()
    {
        $event = $this->get_event();
        app()->goto_event($event, "/deal/$event->id");
    }

    protected function execute_invite()
    {
        $event = $this->get_event();
        app()->goto_event($event, "/invite/$event->id");
    }

    protected function execute_end()
    {
        $event = $this->get_event();
        app()->goto_event($event);
    }

    private function get_event()
    {
        $event = db()->table('events')->row($this->event_id);
        return $event;
    }

    // TRANSITIONS

    protected function after_start()
    {
        $event = $this->get_event();
        if ($event->deal != null)
            return CheckinPageFlow::SHOW_DEAL;
        else
            return $this->after_deal_dialog();
    }

    protected function after_deal_dialog()
    {
        if ($this->has_sent_invite)
            return CheckinPageFlow::END;
        else
            return CheckinPageFlow::INVITE;
    }

}
