<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    function index()
    {
        $this->require_login(TRUE);
        
        enforce_restrictions();

        $user = current_user();
        $college = college();
        
        $data = array(
            'title' => 'My Parties',
            'user' => $user,
            'college' => $college,
            'smile_engine' => new SmileEngine(),
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

}
