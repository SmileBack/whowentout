<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    function index()
    {
        if (!logged_in()) {
            redirect('login');
        }

        enforce_restrictions();
        
        $user = current_user();
        $college = college();
        
        $clock = $college->get_clock();
        $time = $clock->get_time();
        $yesterday = $time->getDay(-1);
        $parties = $college->open_parties($time);
        $checkin_engine = new CheckinEngine();

        $data = array(
            'title' => 'My Parties',
            'user' => $user,
            'college' => $college,
            'closing_time' => load_view('closing_time_view'),
            'doors_are_closed' => ! $college->get_door()->is_open(),
            'open_parties' => $parties,
            'parties_attended' => $checkin_engine->get_recently_attended_parties_for_user($user),
            'has_attended_party' => $user->has_attended_party_on_date($yesterday),
            'smile_engine' => new SmileEngine(),
        );

        if ($data['has_attended_party']) {
            $data['party'] = $user->get_attended_party($yesterday);
        }

        if ($this->flag->missing('user', $user->id, 'has_seen_site_help')) {
            $this->jsaction->ShowSiteHelp();
        }

        $this->load_view('dashboard_view', $data);
    }

    function top_parties()
    {
        print load_view('sections/top_parties_view', array(
                                                          'college' => college(),
                                                     ));
    }

    function past_top_parties()
    {
        $this->load_view('past_top_parties_view', array(
                                                       'html' => get_option('past_top_parties_html', ''),
                                                  ));
    }

    function where_friends_went()
    {
        $this->load_view('where_friends_went_view');
    }

    function where_friends_went_data()
    {
        if (!logged_in())
            $this->json_failure('You must be logged in');

        $user = current_user();
        $date = new XDateTime(post('date'), $user->college->timezone);
        $response = array();
        
        $response['breakdown'] = where_friends_went_pie_chart_data($date);
        $response['friend_galleries_view'] = load_view('friend_galleries_view', array('user' => $user, 'date' => $date));
        
        $this->json($response);
    }

    function site_help()
    {
        $this->flag->set('user', current_user()->id, 'has_seen_site_help');
        print $this->load->view('site_help_view');
    }

    function smile_help()
    {
        $this->flag->set('user', current_user()->id, 'has_seen_smile_help');
        print $this->load->view('smile_help_view');
    }
    
}
