<?php

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
        $this->load_view('admin/admin_view');
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
            $this->load_view('admin/fake_login_view', array(
                                                           'students' => $students,
                                                      ));
        }
    }

    function fake_time()
    {
        $this->check_access();

        $fake_time = post('fake_time');
        $fake_time_point = get_option('fake_time_point');

        if ($fake_time != NULL) {
            $fake_dt = DateTime::createFromFormat('Y-m-d H:i:s', $fake_time, college()->timezone);
            set_fake_time($fake_dt);
        }

        $data = array();
        $delta = time_delta_seconds();

        if (!time_is_faked()) {
            $fake_time_point = array(
                'real_time' => actual_time(),
                'fake_time' => current_time(),
            );
        }

        $data['real_time'] = date_format($fake_time_point['real_time'], 'Y-m-d H:i:s');
        $data['fake_time'] = date_format($fake_time_point['fake_time'], 'Y-m-d H:i:s');
        $data['delta'] = "$delta seconds";

        $this->load_view('admin/fake_time_view', $data);
    }

    function parties()
    {
        $this->check_access();
        $this->load_view('admin/edit_parties_view');
    }

    function places()
    {
        $this->check_access();
        $this->load_view('admin/edit_places_view');
    }

    function users()
    {
        $this->check_access();

        $this->load_view('admin/users_view');
    }

    function destroy_user($user_id)
    {
        $this->check_access();

        $user = user($user_id);
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

        $place = place($place_id);
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

        $place = place($place_id);
        $formatted_date = $date->format('Y-m-d');

        college()->add_party($formatted_date, $place_id);
        set_message("Created party on $formatted_date at $place->name.");
        redirect('admin/parties');
    }

    function delete_party($party_id)
    {
        $this->check_access();

        $party = party($party_id);
        $party_date = $party->date;
        $place_name = $party->place->name;
        $party->delete();
        set_message("Deleted party on $party_date at $place_name.");

        redirect('admin/parties');
    }

    function past_top_parties()
    {
        $this->check_access();

        $this->load_view('admin/edit_past_top_parties_view', array(
                                                                  'html' => get_option('past_top_parties_html', ''),
                                                             ));
    }

    function save_past_top_parties()
    {
        $this->check_access();
        set_option('past_top_parties_html', post('past_top_parties_html'));
        set_message('Saved past top parties html.');
        redirect('admin/past_top_parties');
    }

    function random_checkin($party_id)
    {
        $this->check_access();

        $party = party($party_id);
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
