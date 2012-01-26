<?php

class ViewDealDialogAction extends Action
{

    /* @var $db Database */
    private $db;

    /* @var $auth FacebookAuth */
    private $auth;

    /* @var $invite_engine InviteEngine */
    private $invite_engine;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function __construct()
    {
        $this->db = db();
        $this->auth = build('auth');
        $this->invite_engine = build('invite_engine');
        $this->checkin_engine = build('checkin_engine');
    }

    function execute($event_id)
    {
        if ($this->is_ajax())
            return $this->execute_ajax($event_id);
        elseif (browser::is_desktop())
            return $this->execute_desktop_page($event_id);
        elseif (browser::is_mobile())
            return $this->execute_mobile_page($event_id);
        else
            return null;
    }

    function execute_desktop_page($event_id)
    {
        $event = $this->db->table('events')->row($event_id);

        if (Flow::get() == null)
            Flow::set(new DealDialogFlow($event->id));

        print r::page(array(
            'content' => r::events_date_selector(array('selected_date' => $event->date))
                       . r::event_day(array(
                             'checkin_engine' => $this->checkin_engine,
                             'current_user' => auth()->current_user(),
                             'date' => $event->date,
                         )),
        ));
    }

    function execute_mobile_page($event_id)
    {
        $current_user = auth()->current_user();
        $event = $this->db->table('events')->row($event_id);

        if (Flow::get() == null)
            Flow::set(new DealDialogFlow($event->id));

        print r::mobile_deal_page(array(
            'user' => $current_user,
            'event' => $event,
        ));
    }

    function execute_ajax($event_id)
    {
        $event = $this->db->table('events')->row($event_id);
        $current_user = $this->auth->current_user();
        $has_invited = $this->invite_engine->has_sent_invites($event, $current_user);

        if (Flow::get() == null)
            Flow::set(new DealDialogFlow($event->id));

        print r::deal_popup(array(
            'user' => $current_user,
            'event' => $event,
            'has_invited' => $has_invited,
        ));
    }

}
