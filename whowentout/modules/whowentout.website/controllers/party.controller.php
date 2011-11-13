<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Party extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function page($party_id)
    {
        $this->require_login(TRUE);

        enforce_restrictions();

        $user = current_user();
        $party = XParty::get($party_id);
        $sort = $this->_get_sort();

        $smile_engine = new SmileEngine();

        enforce_restrictions();

        if (!$user->has_attended_party($party)) {
            show_404();
        }

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

        if ($this->flag->missing('user', current_user()->id, 'has_seen_smile_help'))
            $this->jsaction->ShowSmileTip();

        $this->load_view('party', $data);
    }

    function admin($party_id)
    {
        $this->require_admin();

        $party = XParty::get($party_id);

        $this->load_view('party_admin', array(
                                             'party' => $party,
                                        ));
    }

    function admin_checkin_add()
    {
        $this->require_admin();

        $party_id = post('party_id');
        $party = XParty::get($party_id);

        $user_id = post('user_id');
        $user = XUser::get($user_id);

        $checkin_engine = new CheckinEngine();
        $checkin_engine->checkin_user_to_party($user, $party);

        redirect("party/admin/$party->id");
    }

    function admin_checkin_remove()
    {
        $this->require_admin();

        $party_id = post('party_id');
        $party = XParty::get($party_id);

        $user_id = post('user_id');
        $user = XUser::get($user_id);

        $checkin_engine = new CheckinEngine();
        $checkin_engine->remove_user_checkin($user, $party);

        redirect("party/admin/$party->id");
    }

    function pictures($party_id)
    {
        $this->require_login(TRUE);
        enforce_restrictions();

        /* @var $party XParty */
        $party = XParty::get($party_id);

        if ($party->flickr_gallery_id == NULL)
            show_error("This party doesn't have any pictures");

        $gallery = new FlickrGallery($party->flickr_gallery_id);

        if ($gallery) {
            $this->load_view('party_pictures', array(
                                                    'party' => $party,
                                                    'gallery' => $gallery,
                                               ));
        }
    }

    function attach_gallery()
    {
        $party_id = post('party_id');
        $gallery_id = post('flickr_gallery_id');

        /* @var $party XParty */
        $party = XParty::get($party_id);
        $party->flickr_gallery_id = $gallery_id;
        $party->save();

        set_message("Set party Flickr Gallery ID to $gallery_id.");
        redirect("party/admin/{$party->id}");
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
