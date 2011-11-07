<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller
{

    private function can_access()
    {
        return logged_in() && current_user()->is_admin();
    }

    private function check_access()
    {
        if (!$this->can_access())
            show_404();
    }

    function index()
    {
        $this->check_access();
        $this->load_view('admin');
    }

    function fakelogin($user_id = NULL)
    {
        if (!logged_in() || !current_user()->is_admin())
            show_error("Disabled.");

        if ($user_id != NULL) {
            fake_login($user_id);
            redirect('dashboard');
        }
        else {
            $students = college()->get_students();
            $this->load_view('fake_login', array(
                                                'students' => $students,
                                           ));
        }
    }

    function fake_time()
    {
        $this->check_access();

        $clock = college()->get_clock();

        if (post('fake_time') != '') {
            $fake_time_string = post('fake_time');
            $fake_time = new XDateTime($fake_time_string, college()->timezone);
            krumo::dump($fake_time);
            $clock->set_time($fake_time);
        }

        $data = array(
            'delta' => $clock->get_delta(),
            'clock_time' => $clock->get_time(),
        );

        $this->load_view('fake_time', $data);
    }

    function parties()
    {
        $this->check_access();
        $this->load_view('edit_parties');
    }

    function places()
    {
        $this->check_access();
        $this->load_view('edit_places');
    }

    function users()
    {
        $this->check_access();

        $this->load_view('users');
    }

    function destroy_user($user_id)
    {
        $this->check_access();

        $user = XUser::get($user_id);
        $full_name = $user->full_name;
        destroy_user($user->id);
        set_message("Destroyed $full_name.");
        redirect('admin/users');
    }

    function add_place()
    {
        $this->check_access();

        $college = college();
        $name = post('place_name');
        $place = $college->add_place($name);

        set_message("Created place called $place->name.");
        redirect('admin/places');
    }

    function delete_place($place_id)
    {
        $this->check_access();

        $place = XPlace::get($place_id);
        $place_name = $place->name;
        $place->delete();

        set_message("Deleted place $place_name.");
        redirect('admin/places');
    }

    function add_party()
    {
        $this->check_access();

        $date = new DateTime(post('date'), college()->timezone);
        $place_id = post('place_id');

        $place = XPlace::get($place_id);
        $formatted_date = $date->format('Y-m-d');

        college()->add_party($formatted_date, $place_id);
        set_message("Created party on $formatted_date at $place->name.");
        redirect('admin/parties');
    }

    function delete_party($party_id)
    {
        $this->check_access();

        $party = XParty::get($party_id);
        $party_date = $party->date->format("Y-m-d");
        $place_name = $party->place->name;
        $party->delete();
        set_message("Deleted party on $party_date at $place_name.");

        redirect('admin/parties');
    }

    function random_checkin($party_id)
    {
        $this->check_access();

        $party = XParty::get($party_id);

        $checkin_engine = new CheckinEngine();
        $checkin_permission = new CheckinPermission();

        $user = get_random_user($party->id);

        if ($checkin_permission->check($user, $party)) {
            $checkin_engine->checkin_user_to_party($user, $party);
            set_message("Randomly checked in $user->full_name to {$party->place->name} on " . $party->date->format('Y-m-d') . ".");
        }
        else {
            set_message("Couldn't checkin $user->full_name.");
        }

        redirect('admin/parties');
    }

    function featured_message()
    {
        $this->check_access();

        $featured_message = $this->option->get('featured_message', '');
        print r('page', array(
                             'page_content' => r('admin_featured_message', array(
                                                                             'featured_message' => $featured_message,
                                                                        ))
                        ));
    }

    function featured_message_save()
    {
        $featured_message = post('featured_message');
        $this->option->set('featured_message', $featured_message);
        set_message('Saved the featured message.');
        redirect('admin/featured_message');
    }

    function featured_date()
    {
        $this->check_access();

        $featured_date = $this->option->get('featured_date_string');
        print r('page', array(
                             'page_content' => r('admin_featured_date', array(
                                                                             'featured_date_string' => $featured_date,
                                                                        ))
                        ));
    }

    function featured_date_save()
    {
        $this->check_access();

        $featured_date_string = post('featured_date_string');

        if ($featured_date_string == '') {
            $this->option->delete('featured_date_string');
            set_message("Removed featured date.");
        }
        elseif ($this->is_valid_date_string($featured_date_string)) {
            $this->option->set('featured_date_string', $featured_date_string);
            set_message("Saved featured date.");
        }
        else {
            set_message("Invalid date. Must be of the format 2011-10-24");
        }

        redirect('admin/featured_date');
    }

    private function is_valid_date_string($date_string)
    {
        $valid = TRUE;
        try {
            $featured_date = new XDateTime($date_string, new DateTimeZone('UTC'));
        }
        catch (Exception $e) {
            $valid = FALSE;
        }
        return $valid && $featured_date->format('Y-m-d') == $date_string;
    }

}
