<?php

class XParty extends XObject
{
    protected static $table = 'parties';

    function get_place()
    {
        return XPlace::get($this->place_id);
    }

    function get_college()
    {
        return $this->place->college;
    }

    /**
     * @return XDateTime
     */
    function get_date()
    {
        $timezone = $this->college->timezone;
        return new XDateTime($this->data['date'], $timezone);
    }

    function attendees($sort = 'checkin_time')
    {
        $query = $this->attendees_query($sort);
        return XObject::load_objects('XUser', $query);
    }

    function attendees_query($sort = 'checkin_time')
    {
        $query = $this->db()->select('user_id AS id')
                ->from('party_attendees')
                ->join('users', 'users.id = party_attendees.user_id')
                ->where('party_id', $this->id);

        $this->apply_attendee_sort($query, $sort);

        return $query;
    }

    function apply_attendee_sort($query, $sort)
    {
        if ($sort == 'checkin_time') {
            $query->order_by('checkin_time', 'desc');
        }
        elseif ($sort == 'gender') {
            $order = $this->attendees_query_gender_sort_order();
            $query->order_by('gender', $order)
                    ->order_by('checkin_time', 'desc');
        }
        elseif ($sort == 'name') {
            $query->order_by('first_name', 'asc')
                    ->order_by('last_name', 'asc');
        }

        return $query;
    }

    /**
     * @param  $attendee XUser
     * @return array
     *      An associative array of insert positions.
     *      It will be of the form [sort] => [id], where [id] is the id of the user the $attendee is right be after.
     */
    function attendee_insert_positions($attendee)
    {
        $sorts = array('checkin_time', 'gender', 'name');

        $insert_positions = array();
        foreach ($sorts as $sort) {
            $all_attendees = $this->attendees($sort);
            $insert_positions[$sort] = $this->get_prev_attendee_id($attendee, $all_attendees);
        }
        return $insert_positions;
    }

    private function get_prev_attendee_id($attendee, $all_attendees)
    {
        $count = count($all_attendees);

        if (!$count)
            return FALSE;

        if ($all_attendees[0]->id == $attendee->id)
            return 'first';

        for ($index = 0; $index < $count; $index++) {
            if ($all_attendees[$index]->id == $attendee->id)
                return $all_attendees[$index - 1]->id;
        }

        return FALSE;
    }

    private function attendees_query_gender_sort_order()
    {
        if (!logged_in())
            return 'desc';
        if (current_user()->gender == 'M')
            return 'desc';
        if (current_user()->gender == 'F')
            return 'asc';
    }

    function has_photo_gallery()
    {
        return $this->flickr_gallery_id != NULL;
    }

    private $photo_gallery;
    /**
     * @return FlickrGallery
     */
    function get_photo_gallery()
    {
        if (!$this->has_photo_gallery())
            return NULL;

        if (!$this->photo_gallery)
            $this->photo_gallery = new FlickrGallery($this->flickr_gallery_id);

        return $this->photo_gallery;
    }

    function get_count()
    {
        return $this->attendees_query()->count_all_results();
    }

    function get_female_count()
    {
        return $this->attendees_query()->where('gender', 'F')->count_all_results();
    }

    function get_male_count()
    {
        return $this->attendees_query()->where('gender', 'M')->count_all_results();
    }

    function recent_attendees($count = 5)
    {
        $query = $this->attendees_query('checkin_time')->limit($count);
        return XObject::load_objects('XUser', $query);
    }

    function chat_is_closed()
    {
        return !$this->chat_is_open();
    }

    function chat_is_open()
    {
        return $this->college->get_time()->getTimestamp() < $this->chat_close_time()->getTimestamp();
    }

    function chat_close_time()
    {
        $date = $this->date;
        $date->modify('next Thursday');
        return $date;
    }

    function smiling_is_closed()
    {
        return !$this->smiling_is_open();
    }

    function smiling_is_open()
    {
        return $this->college->get_time()->getTimestamp() < $this->smiling_close_time()->getTimestamp();
    }

    function smiling_close_time($local = FALSE)
    {
        return $this->chat_close_time($local);
    }

    function send_invitation($from, $student_id)
    {
        $ci =& get_instance();
        $from = XUser::get($from);

        $student = $this->db()->from('college_students')
                ->where('id', $student_id)
                ->get()->row();

        if (!$from || !$student)
            return FALSE;

        $receiver = array(
            'full_name' => $student->student_full_name,
            'email' => $student->student_email,
        );

        $this->db()->insert('party_invitations', array(
                                                      'created_at' => $this->college->get_time()->formatMySqlTimestamp(),
                                                      'party_id' => $this->id,
                                                      'sender_id' => $from->id,
                                                      'college_student_id' => $student->id,
                                                 ));

        f()->trigger('party_invite_sent', array(
                                               'party' => $this,
                                               'sender' => $from,
                                               'receiver' => (object)$receiver,
                                          ));

        return TRUE;
    }

    function to_array()
    {
        return array(
            'id' => $this->id,
            'place_name' => $this->place->name,
            'date' => $this->date,
        );
    }

}
