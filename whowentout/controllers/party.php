<?php

class Party extends MY_Controller
{

    function page($party_id)
    {
        $user = current_user();
        $party = party($party_id);
        $sort = $this->_get_sort();

        enforce_restrictions();

        if (!$user->has_attended_party($party)) {
            show_404();
        }

        raise_event('page_load', array(
                                      'url' => uri_string(),
                                 ));

        $data = array(
            'title' => 'Party',
            'party' => $party,
            'user' => $user,
            'sort' => $sort,
            'party_attendees' => $party->attendees($sort),
            'profile_pic_size' => $this->config->item('profile_pic_size'),
            'smiles_left' => $user->smiles_left($party->id),
        );

        $this->load_view('party_view', $data);
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
