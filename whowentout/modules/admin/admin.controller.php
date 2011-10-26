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
        //    $this->check_access();

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
            $clock->set_time($fake_time);
        }

        $data = array(
            'delta' => $clock->get_delta(),
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
        $party_date = $party->date;
        $place_name = $party->place->name;
        $party->delete();
        set_message("Deleted party on $party_date at $place_name.");

        redirect('admin/parties');
    }
    
    function random_checkin($party_id)
    {
        $this->check_access();

        $party = XParty::get($party_id);
        $user = get_random_user($party->id);

        if ($user->can_checkin($party)) {
            $user->checkin($party->id);
            set_message("Randomly checked in $user->full_name to {$party->place->name} on $party->date.");
        }
        else {
            set_message("Couldn't checkin $user->full_name. " . get_reason_message($user->reason()));
        }

        redirect('admin/parties');
    }

}
