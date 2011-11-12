<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    function index()
    {
        $this->require_login(TRUE);

        enforce_restrictions();

        $user = current_user();
        $college = college();

        $party_groups = $this->get_visible_party_groups($user, $college);

        $data = array(
            'title' => 'My Parties',
            'user' => $user,
            'college' => $college,
            'smile_engine' => new SmileEngine(),
            'party_groups' => $party_groups,
        );

        if ($this->flag->missing('user', $user->id, 'has_seen_site_help')) {
            $this->jsaction->ShowSiteHelp();
        }

        $this->load_view('dashboard', $data);
    }

    function where_friends_went()
    {
        $this->require_login(TRUE);
        enforce_restrictions();

        $this->load_view('where_friends_went');
    }

    function where_friends_went_data()
    {
        if (!logged_in())
            $this->json_failure('You must be logged in');

        $user = current_user();
        $date = new XDateTime(post('date'), $user->college->timezone);
        $response = array();

        $response['breakdown'] = where_friends_went_pie_chart_data($date);

        $response['friend_galleries_view'] = r('friend_galleries', array('user' => $user,
                                                                        'date' => $date));

        $this->json($response);
    }

    private function get_visible_party_groups(XUser $user, XCollege $college)
    {
        $featured_party_groups = $this->get_featured_party_groups($user, $college);
        $past_party_groups = $this->get_past_party_groups($user, $college);
        return array_merge($featured_party_groups, $past_party_groups);
    }

    private function get_featured_party_groups(XUser $user, XCollege $college)
    {
        $groups = array();

        $featured_date_strings = $this->option->get('featured_date_strings');
        if ( ! is_array($featured_date_strings) )
            return array();

        foreach ($featured_date_strings as $date_string) {
            if ($date_string == '')
                continue;
            
            $featured_party_group_date = new XDateTime($date_string, $college->timezone);
            $featured_party_group = new PartyGroup($college->get_clock(), $featured_party_group_date);

            //only featured if they haven't checked in
            if ( ! $featured_party_group->get_selected_party($user))
                $groups[] = $featured_party_group;
        }

        return $groups;
    }

    private function get_past_party_groups(XUser $user, XCollege $college)
    {
        $party_groups = array();
        
        $checkin_engine = new CheckinEngine();
        $parties_attended = $checkin_engine->get_recently_attended_parties_for_user($user);

        /* @var $party XParty */
        foreach ($parties_attended as $party) {
            $group = new PartyGroup($college->get_clock(), $party->date);
            $party_groups[] = $group;
        }

        return $party_groups;
    }

}
