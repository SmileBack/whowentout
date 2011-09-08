<?php

class XParty extends XObject
{
    protected static $table = 'parties';

    function get_place()
    {
        return place($this->place_id);
    }

    function get_college()
    {
        return $this->place->college;
    }

    function get_admin()
    {
        if ($this->admin_id == NULL)
            return NULL;

        return user($this->admin_id);
    }

    function attendees($sort = 'checkin_time')
    {
        $query = $this->attendees_query($sort);
        return $this->load_objects('XUser', $query);
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
            $query->order_by('party_attendees.id', 'desc');
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

    function get_online_user_ids()
    {
        $a_little_while_ago = current_time()->modify('-10 seconds')->format('Y-m-d H:i:s');
        $rows = $this->db()->select('id')
                ->from('users')
                ->where('last_ping >', $a_little_while_ago)
                ->get()->result();
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row->id;
        }
        return $ids;
    }

    function recent_attendees()
    {
        $attendees = array();

        $rows = $this->db()->select('user_id AS id')
                ->from('party_attendees')
                ->join('users', 'users.id = party_attendees.user_id')
                ->where('party_id', $this->id)
                ->order_by('checkin_time', 'desc')
                ->limit(5)
                ->get()->result();

        foreach ($rows as $row) {
            $attendees[] = user($row->id);
        }

        return $attendees;
    }

    function to_array()
    {
        return array(
            'id' => $this->id,
            'place_name' => $this->place->name,
        );
    }

}
