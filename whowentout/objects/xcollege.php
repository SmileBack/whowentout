<?php

class XCollege extends XObject
{

    protected static $table = 'colleges';

    static function current()
    {
        $ci =& get_instance();
        return XCollege::get($ci->config->item('selected_college_id'));
    }

    function add_party($date, $place_id)
    {
        if ($date instanceof DateTime)
            $date = date_format($date, 'Y-m-d');

        $party = XParty::create(array(
                                     'place_id' => $place_id,
                                     'date' => $date,
                                ));
        return $party;
    }

    function add_place($name)
    {
        $place = XPlace::create(array(
                                     'college_id' => $this->id,
                                     'name' => $name,
                                ));

        return $place;
    }

    /**
     * Get all of the parties that the user can check into at $time.
     * @param DateTime $time
     * @return array
     *   An array of XParty objects.
     */
    function open_parties($time)
    {
        $parties = array();
        $query = $this->_get_open_parties_query($time);
        return $this->load_objects('XParty', $query);
    }

    /**
     * @return DateTime
     */
    function current_time($local = FALSE, $current_time = NULL)
    {
        $dt = actual_time();

        if (time_is_faked()) {
            $fake_time_point = get_option('fake_time_point');
            $delta = time_delta_seconds();
            $dt = $dt->modify("+$delta seconds");
        }

        if ($current_time != NULL)
            $dt = $current_time;

        return $local ? $this->make_local($dt) : $this->make_gmt($dt);
    }

    function make_gmt($time)
    {
        $time = clone $time;
        $time->setTimezone(new DateTimeZone('UTC'));
        return $time;
    }

    function make_local($time)
    {
        $time = clone $time;
        $time->setTimezone($this->timezone);
        return $time;
    }

    function get_timezone()
    {
        return new DateTimeZone('America/New_York');
    }

    function get_students()
    {
        $query = $this->db()->select('id')
                ->from('users')
                ->where('college_id', $this->id)
                ->order_by('first_name', 'ASC');
        return $this->load_objects('XUser', $query);
    }

    function get_recent_dates()
    {
        $rows = $this->db()->select('DISTINCT(date) AS date')
                ->from('parties')
                ->join('places', 'parties.place_id = places.id')
                ->where('places.college_id', $this->id)
                ->order_by('date', 'desc')
                ->limit(3)
                ->get()->result();
        $dates = array();
        foreach ($rows as $row) {
            $dates[] = new DateTime($row->date, $this->timezone);
        }
        return $dates;
    }

    function find_student($full_name)
    {
        $parts = preg_split('/\s+/', trim($full_name));
        $first_name = $parts[0];
        $last_name = $parts[count($parts) - 1];
        $full_name = "$first_name $last_name";

        $college_id = $this->id;
        $students = $this->db()->from('college_students')
                ->where('college_id', $college_id)
                ->where('student_full_name', trim($full_name))
                ->get()->result();

        if (empty($students)) {
            $variations = $this->student_name_variations($full_name);
            if (!empty($variations)) {
                $students = $this->db()->from('college_students')
                        ->where('college_id', $college_id)
                        ->where_in('student_full_name', $variations)
                        ->get()->result();
            }
        }

        if (empty($students)) {
            $students = $this->db()->from('college_students')
                    ->where('college_id', $college_id)
                    ->like('student_full_name', substr($first_name, 0, 3), 'after')
                    ->like('student_full_name', " $last_name", 'before')
                    ->get()->result();
        }

        return count($students) == 1 ? $students[0] : FALSE;
    }

    function student_name_variations($full_name)
    {
        list($first_name, $last_name) = preg_split('/\s+/', $full_name);
        $rows = $this->db()->select('name')
                ->from('common_nicknames')
                ->where('nickname', $first_name)
                ->get()->result();
        $variations = array();
        foreach ($rows as $row) {
            $variations[] = "$row->name $last_name";
        }
        return $variations;
    }

    /**
     * Modify current time so that it matches the local time at this college.
     * @param string $local_time_string
     *  A time string of the format 2011-10-14 22:05:00
     */
    function set_fake_local_time($local_time_string)
    {
        $tz = $this->current_time(TRUE)->format('O');
        $time_string = "$local_time_string $tz";
        $dt = new DateTime($time_string);
        set_fake_time($dt);
    }


    /**
     * @param  $day_offset
     * @param bool $local
     * @param DateTime $current_time
     *      Pass in this parameter if you want to override what the current time is.
     * @return DateTime
     *      The day $day_offset days away from the current time with the time set to 12 am.
     */
    function day($day_offset, $local = FALSE, $current_time = NULL)
    {
        if ($current_time == NULL)
            $current_time = $this->current_time();

        $current_local_time = $this->make_local($current_time);
        $current_local_time->setTime(0, 0, 0);

        if ($day_offset > 0) {
            $current_local_time->modify("+{$day_offset} day");
        }
        elseif ($day_offset < 0) {
            $current_local_time->modify("{$day_offset} day");
        }

        return $local ? $this->make_local($current_local_time)
                : $this->make_gmt($current_local_time);
    }

    function party_day($party_day_offset, $local = FALSE, $current_time = NULL)
    {
        return $this->day_of_type('party', $party_day_offset, $local, $current_time);
    }

    function this_week_party_day($party_day_offset, $local = FALSE, $current_time = NULL)
    {
        if ($current_time == NULL)
            $current_time = current_time();

        $today = $this->day(0, TRUE, $current_time);

        $party_day = clone $today;

        if (!($today->format('l') == 'Thursday'))
            $party_day->modify('last Thursday');

        return $this->day($party_day_offset, $local, $party_day);
    }

    function day_of_type($day_type, $target_offset, $local = FALSE, $current_time = NULL)
    {
        $max_limit = 30;
        $target_offset = intval($target_offset);
        $filtered_offset = 0;
        $actual_offset = 0;

        $step = $target_offset > 0 ? 1 : -1;
        $filter = "is_{$day_type}_day";

        $cur_day = $this->day($actual_offset, $local, $current_time);

        //today won't always work since today might satisfy the conditions
        if ($target_offset == 0)
            return $this->$filter(clone $cur_day) ? $cur_day : FALSE;

        do {
            $actual_offset += $step;
            $cur_day = $this->day($actual_offset, $local, $current_time);

            if ($this->$filter(clone $cur_day))
                $filtered_offset += $step;

            if ($actual_offset > $max_limit)
                throw new Exception('Exceeded the offset limit.');

        } while ($filtered_offset != $target_offset);

        return $local ? $this->make_local($cur_day)
                : $this->make_gmt($cur_day);
    }

    function is_party_day(DateTime $day)
    {
        $day = $this->make_local($day);
        $party_days = array('Thursday', 'Friday', 'Saturday');
        return in_array($day->format('l'), $party_days);
    }

    function is_checkin_day(DateTime $day)
    {
        $day = $this->make_local($day);

        $prev_day = clone $day;
        $prev_day->modify('-1 day');

        return $this->is_party_day($prev_day);
    }

    function is_initial_checkin_day(DateTime $day)
    {
        $day = $this->make_local($day);

        $prev_day = clone $day;
        $prev_day->modify('-1 day');

        return !$this->is_checkin_day($prev_day)
               && $this->is_checkin_day($day);
    }

    function is_final_checkin_day(DateTime $day)
    {
        $day = $this->make_local($day);

        $next_day = clone $day;
        $next_day->modify('+1 day');

        return $this->is_checkin_day($day)
               && !$this->is_checkin_day($next_day);
    }

    function is_non_party_day(DateTime $day)
    {
        return !$this->is_party_day($day);
    }

    function checkins_begin_time($local = FALSE, $current_time = NULL)
    {
        if ($current_time == NULL)
            $current_time = current_time();

        $begin_day = $this->day_of_type('initial_checkin', 0, $local, $current_time);

        if (!$begin_day) { // if today isn't a day where the checkins begin
            $begin_day = $this->day_of_type('initial_checkin', 1, $local, $current_time);
        }

        $begin_time = $this->get_opening_time($local, $begin_day);
        //if time has passed get next begin time
        if ($current_time->getTimestamp() > $begin_time->getTimestamp()) {
            $begin_day = $this->day_of_type('initial_checkin', 1, $local, $current_time);
            $begin_time = $this->get_opening_time($local, $begin_day);
        }

        return $begin_time;
    }

    function checkins_end_time($local = FALSE, $current_time = NULL)
    {
        if ($current_time == NULL)
            $current_time = current_time();

        $end_day = $this->day_of_type('final_checkin', 0, $local, $current_time);
        if (!$end_day) {
            $end_day = $this->day_of_type('final_checkin', 1, $local, $current_time);
        }

        $end_time = $this->get_closing_time($local, $end_day);

        //if time has passed get next end time
        if ($current_time->getTimestamp() > $end_time->getTimestamp()) {
            $end_day = $this->day_of_type('final_checkin', 2, $local, $current_time);
            $end_time = $this->get_closing_time($local, $end_day);
        }

        return $end_time;
    }

    function within_checkin_periods($current_time = NULL)
    {
        return !($this->checkins_end_time(FALSE, $current_time)->getTimestamp()
                 > $this->checkins_begin_time(FALSE, $current_time)->getTimestamp());
    }

    /**
     * Gives you the date for today at current college (12am).
     * @param bool $local
     * @return DateTime
     */
    function today($local = FALSE, $current_time = NULL)
    {
        return $this->day(0, $local, $current_time);
    }

    function yesterday($local = FALSE, $current_time = NULL)
    {
        return $this->day(-1, $local, $current_time);
    }

    function tomorrow($local = FALSE, $current_time = NULL)
    {
        return $this->day(+1, $local, $current_time);
    }

    /**
     * @return int
     *   The number of seconds until the doors are closed. If the doors have already
     *   closed, 0 will be returned.
     */
    function get_seconds_until_close()
    {
        $delta = $this->get_closing_time()->getTimestamp()
                 - $this->current_time()->getTimestamp();
        return max($delta, 0);
    }

    function doors_are_closed($current_time = NULL)
    {
        return !$this->doors_are_open($current_time);
    }

    function doors_are_open($current_time = NULL)
    {
        if (!$current_time)
            $current_time = $this->current_time();

        return $this->get_opening_time($current_time)->getTimestamp()
               > $this->get_closing_time($current_time)->getTimestamp()
               && $this->is_checkin_day($current_time);
    }

    /**
     * Return the GMT time for when the doors at the current college are next open for checkin.
     * @param $local bool
     *      If true, the time will be converted to the local timezone of this college.
     *      If false, the time will be returned in UTC.
     * @return DateTime
     */
    function get_opening_time($local = FALSE, $current_time = NULL)
    {
        $opening_time = $this->today(TRUE, $current_time)->setTime(2, 0, 0);

        //opening time has already passed to return the next opening time.
        if ($this->current_time(TRUE, $current_time) >= $opening_time)
            $opening_time = $opening_time->modify('+1 day');

        return $local ? $this->make_local($opening_time)
                : $this->make_gmt($opening_time);
    }

    /**
     * Return the GMT time for when the doors at the current college are next closed for checkin.
     * @param $local bool
     *      If true, the time will be converted to the local timezone of this college.
     *      If false, the time will be returned in UTC.
     * @return DateTime
     */
    function get_closing_time($local = FALSE, $current_time = NULL)
    {
        $closing_time = $this->tomorrow(TRUE, $current_time)->setTime(0, 0, 0);

        if ($this->current_time(TRUE, $current_time) >= $closing_time)
            $closing_time = $closing_time->modify('+1 day');

        return $local ? $this->make_local($closing_time)
                : $this->make_gmt($closing_time);
    }

    function get_places()
    {
        $query = $this->get_places_query();
        return $this->load_objects('XPlace', $query);
    }

    function get_places_query()
    {
        return $this->db()->select('id')
                ->from('places')
                ->where('college_id', $this->id)
                ->order_by('name', 'ASC');
    }

    function parties($limit = 10)
    {
        $query = $this->get_parties_query()->limit($limit);
        return $this->load_objects('XParty', $query);
    }

    function get_parties()
    {
        $query = $this->get_parties_query();
        return $this->load_objects('XParty', $query);
    }

    function get_parties_query()
    {
        return $this->db()->select('parties.id AS id')
                ->from('parties')
                ->join('places', 'parties.place_id = places.id')
                ->where('college_id', $this->id)
                ->order_by('date', 'ASC')
                ->order_by('name', 'ASC');
    }

    function top_parties()
    {
        $time = $this->yesterday(TRUE);

        $sql = "SELECT party_id AS id, party_date, LEAST(males, females) AS score_a, males+females AS score_b FROM
            (
              SELECT party_id, parties.date AS party_date, SUM(gender = 'M') AS males, SUM(gender = 'F') as females
              FROM party_attendees
                INNER JOIN parties ON party_attendees.party_id = parties.id
                INNER JOIN users ON party_attendees.user_id = users.id
              WHERE parties.date = ?    
              GROUP BY party_id
            ) AS party_counts
            ORDER BY score_a DESC, score_b DESC LIMIT 3";

        $query = $this->db()->query($sql, array(date_format($time, 'Y-m-d')));

        return $this->load_objects('XParty', $query);
    }

    function parties_on(DateTime $date)
    {
        $date = $this->make_local($date);
        $query = $this->db()
                ->select('parties.id AS id')
                ->from('parties')
                ->where(array(
                             'college_id' => $this->id,
                             'date' => date_format($date, 'Y-m-d'),
                        ))
                ->join('places', 'parties.place_id = places.id');
        return $this->load_objects('XParty', $query);
    }

    function update_offline_users()
    {
        $a_little_while_ago = current_time()->modify('-10 seconds')->format('Y-m-d H:i:s');
        //users that should be offline based on last ping but aren't marked as offline
        $rows = $this->db()->select('id')
                ->from('users')
                ->where('college_id', $this->id)
                ->where('last_ping <', $a_little_while_ago);
        $users = $this->load_objects('XUser', $rows);

        $uids = array();
        foreach ($users as $user) {
            $user->ping_leaving_site();
            $uids[] = $user->id;
        }
        return $uids;
    }

    function get_online_users_ids()
    {
        $query = $this->db()->select('id')
                ->from('users')
                ->where('college_id', $this->id)
                ->where('last_ping IS NOT NULL');

        $ids = array();
        foreach ($query->get()->result() as $row) {
            $ids[] = $row->id;
        }
        return $ids;
    }

    private function _get_open_parties_query($time)
    {
        //open parties today means parties that occured yesterday
        $time = $this->make_local($time)->modify('-1 day');

        return $this->db()
                ->select('parties.id AS id')
                ->from('parties')
                ->where(array(
                             'college_id' => $this->id,
                             'date' => $time->format('Y-m-d'),
                        ))
                ->join('places', 'parties.place_id = places.id');
    }

    function format_time(DateTime $dt, $format = 'default')
    {
        $formats = array('default' => 'l, M. jS', 'short' => 'D, M. jS');

        $dt = $this->make_local($dt);
        return $dt->format($formats[$format]);
    }

    function format_relative_night(DateTime $dt)
    {
        $dt = $this->make_local($dt);
        $dt->setTime(0, 0, 0);
        return $dt->format('l') . ' night';
    }

    function next_checkin_day_for(XUser $user)
    {
        $party = $user->get_checked_in_party($this->today());
        $checked_in = $party != NULL;
        
        $next_checkin_day = $this->day_of_type('checkin', 0, TRUE);
        if (!$next_checkin_day || $checked_in)
            $next_checkin_day = $this->day_of_type('checkin', 1, TRUE);

        return $next_checkin_day;
    }

    function next_party_day_for(XUser $user)
    {
        $checkin_day = $this->next_checkin_day_for($user);
        $checkin_day->modify('-1 day');
        return $checkin_day;
    }

    function to_array()
    {
        $college = array();
        $college['id'] = $this->id;
        $college['currentTime'] = current_time()->getTimestamp();
        $college['doorsClosingTime'] = $this->get_closing_time()->getTimestamp();
        $college['doorsOpeningTime'] = $this->get_opening_time()->getTimestamp();
        $college['yesterdayTime'] = $this->yesterday()->getTimestamp();
        $college['tomorrowTime'] = $this->tomorrow()->getTimestamp();
        $college['doorsOpen'] = $this->doors_are_open();
        return $college;
    }

}
