<?php


class Events_Controller extends Controller
{

    function __construct()
    {
    }

    function test()
    {
        db()->destroy_all_tables();
//        $user_id = 9;
//        $users_table = db()->table('users');
//        $ven = $users_table->row($user_id);
//
//        $facebook = factory()->build('facebook');
//        $facebook_id = $ven->facebook_id;
//
//        $friend_source = new FacebookFriendSource($facebook, $facebook_id);
//
//        $updater = new FacebookFriendsUpdater(db(), $friend_source);
//
//        $updater->update_facebook_friends($ven);
    }

    function index($date = null)
    {
        $current_user = auth()->current_user();

        if (isset($_SESSION['checkins_create_event_id']))
            redirect('checkins/create');

        if ($date == null)
            $date = app()->clock()->today();
        else {
            $date = DateTime::createFromFormat('Ymd', $date);
            $date->setTime(0, 0, 0);
        }

        /* @var $checkin_engine CheckinEngine */
        $checkin_engine = factory()->build('checkin_engine');

        $checkin = $checkin_engine->get_checkin_on_date($current_user, $date);
        $selected_event = $checkin ? $checkin->event : null;

        print r::page(array(
                           'content' => r::events_view(array(
                                                            'date' => $date,
                                                            'checkin' => $checkin,
                                                            'selected_event' => $selected_event,
                                                       )),
                      ));
    }

    /**
     * @return CheckinEngine
     */
    private function checkin_engine()
    {
        return factory()->build('checkin_engine');
    }

    private function default_date()
    {
        return app()->clock()->today();
    }

    function invite()
    {
        print r::page(array(
                           'content' => r::event_invite(),
                      ));
    }

}
