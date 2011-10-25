<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Party extends MY_Controller
{

    function page($party_id)
    {
        $user = current_user();
        $party = XParty::get($party_id);
        $sort = $this->_get_sort();

        $smile_engine = new SmileEngine();

        enforce_restrictions();

        if (!$user->has_attended_party($party)) {
            show_404();
        }

        if ($this->input->get('src') == 'smiles') {
            $this->jsaction->ShowSpotlight('.party_notices', 1000);
            redirect("party/$party_id");
        }

        f()->trigger('page_load', array(
                                       'url' => uri_string(),
                                  ));

        $this->benchmark->mark('party_attendees_start');
        $party_attendees = $party->attendees($sort);
        $this->benchmark->mark('party_attendees_end');

        $data = array(
            'title' => "{$party->place->name} Gallery",
            'party' => $party,
            'user' => $user,
            'sort' => $sort,
            'party_attendees' => $party_attendees,
            'profile_pic_size' => $this->config->item('profile_pic_size'),
            'smile_engine' => $smile_engine,
            'smiles_left' => $smile_engine->get_num_smiles_left_to_give($user, $party),
        );

        if ($this->flag->missing('user', $user->id, 'has_seen_smile_help'))
            $this->jsaction->ShowSmileHelpTip();

        $this->load_view('party_view', $data);
    }

    function invite()
    {
        $this->require_login();
        
        $college_student_id = post('name');
        $party_id = post('party_id');

        $party = XParty::get($party_id);

        if (!$party) {
            set_message("Party with id $party_id doesn't exist.");
            redirect('/');
        }

        $party->send_invitation(current_user(), $college_student_id);
        set_message('Sent invitation');

        redirect("party/$party->id");
    }

    function _get_sort()
    {
        $possible_sorts = array('checkin_time', 'name', 'gender');
        $sort = $this->input->get('sort');
        return in_array($sort, $possible_sorts)
                ? $sort
                : $possible_sorts[0];
    }

}
