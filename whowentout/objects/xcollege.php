<?php

class XCollege extends XObject
{
  
  protected static $table = 'colleges';
  
  static function current() {
    return XCollege::get( 1 );
  }
  
  function add_party($date, $place_id) {
    if ($date instanceof DateTime)
      $date = date_format($date, 'Y-m-d');
    
    $party = XParty::create(array(
      'place_id' => $place_id,
      'date' => $date,
    ));
    return $party;
  }
  
  function add_place($name) {
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
   *   An array of iparty objects.
   */
  function open_parties($time) {
    $parties = array();
    $query = $this->_get_open_parties_query($time);
    return $this->load_objects('XParty', $query);
  }
  
  /**
   * @return DateTime
   */
  function current_time($local = FALSE) {
    $dt = actual_time();

    if ( time_is_faked() ) {
      $fake_time_point = get_option('fake_time_point');
      $delta = time_delta_seconds();
      $dt = $dt->modify("+$delta seconds");
    }

    return $local ? $this->make_local($dt) : $this->make_gmt($dt);
  }
  
  function make_gmt($time) {
    $time = clone $time;
    $time->setTimezone(new DateTimeZone('UTC'));
    return $time;
  }

  function make_local($time) {
    $time = clone $time;
    $time->setTimezone( $this->timezone );
    return $time;
  }
  
  function get_timezone() {
    return new DateTimeZone('America/Los_Angeles');
  }
  
  function get_students() {
    $query = $this->db()->select('id')
                        ->from('users')
                        ->where('college_id', $this->id)
                        ->order_by('first_name', 'ASC');
    return $this->load_objects('XUser', $query);
  }
  
  function get_recent_dates() {
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
  
  function find_student($full_name) {
    $parts = preg_split('/\s+/', trim($full_name));
    $first_name = $parts[0];
    $last_name = $parts[ count($parts) - 1 ];
    $full_name = "$first_name $last_name";
    
    $college_id = $this->id;
    $students = $this->db()->from('college_students')
                     ->where('college_id', $college_id)
                     ->where('student_full_name', trim($full_name))
                     ->get()->result();
    
    if (empty($students)) {
      $variations = $this->student_name_variations($full_name);
      if ( ! empty($variations) ) {
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
  
  function student_name_variations($full_name) {
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
  function set_fake_local_time($local_time_string) {
    $tz = $this->current_time(TRUE)->format('O');
    $time_string = "$local_time_string $tz";
    $dt = new DateTime($time_string);
    set_fake_time($dt);
  }
  
  function day($day_offset, $local = FALSE) {
    $current_local_time = $this->current_time(TRUE);
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
  
  function party_day($party_day_offset, $local = FALSE) {
    $party_day_offset = intval($party_day_offset);
    $current_party_day_offset = 0;
    $actual_offset = 0;
    
    //today won't always work since today might not be a party day
    if ($party_day_offset == 0)
      return FALSE;
    
    $step = $party_day_offset > 0 ? 1 : -1;
    do {
      $current_party_day = $this->day($actual_offset);
      $actual_offset += $step;
      if ($this->is_party_day($current_party_day))
        $current_party_day_offset += $step;
    } while ($current_party_day_offset != $party_day_offset);
    
    return $local ? $this->make_local($current_party_day)
                  : $this->make_gmt($current_party_day);
  }
  
  function is_party_day(DateTime $day) {
    $day = $this->make_local($day);
    $party_days = array('Thursday', 'Friday', 'Saturday');
    return in_array($day->format('l'), $party_days);
  }
  
  /**
   * Gives you the date for today at current college (12am).
   * @param bool $local
   * @return DateTime
   */
  function today($local = FALSE) {
    return $this->day(0, $local);
  }
  
  function yesterday($local = FALSE) {
    return $this->day(-1, $local);
  }
  
  function tomorrow($local = FALSE) {
    return $this->day(+1, $local);
  }
  
  /**
   * @return int
   *   The number of seconds until the doors are closed. If the doors have already
   *   closed, 0 will be returned.
   */
  function get_seconds_until_close() {
    $delta = $this->get_closing_time()->getTimestamp()
           - $this->current_time()->getTimestamp();
    return max($delta, 0);
  }
  
  function doors_are_closed() {
    return ! $this->doors_are_open();
  }
  
  function doors_are_open() {    
    return $this->get_opening_time()->getTimestamp() > $this->get_closing_time()->getTimestamp();
  }
  
  /**
   * Return the GMT time for when the doors at the current college are next open for checkin.
   * @return DateTime
   */
  function get_opening_time($local = FALSE) {
    $opening_time = $this->today(TRUE)->setTime(1, 0, 0);
    
    //opening time has already passed to return the next opening time.
    if ( $this->current_time(TRUE) >= $opening_time )
      $opening_time = $opening_time->modify('+1 day');
    
    return $local ? $this->make_local($opening_time)
                  : $this->make_gmt($opening_time);
  }
  
  /**
   * Return the GMT time for when the doors at the current college are next closed for checkin.
   * @return DateTime
   */
  function get_closing_time($local = FALSE) {
    $closing_time = $this->today(TRUE)->setTime(12 + 11, 0, 0);
    
    if ( $this->current_time(TRUE) >= $closing_time )
      $closing_time = $closing_time->modify('+1 day');
    
    return $local ? $this->make_local($closing_time)
                  : $this->make_gmt($closing_time);
  }
  
  function get_places() {
    $query = $this->get_places_query();
    return $this->load_objects('XPlace', $query);
  }
  
  function get_places_query() {
    return $this->db()->select('id')
                      ->from('places')
                      ->where('college_id', $this->id)
                      ->order_by('name', 'ASC');
  }
  
  function parties($limit = 10) {
    $query = $this->get_parties_query()->limit($limit);
    return $this->load_objects('XParty', $query);
  }
  
  function get_parties() {
    $query = $this->get_parties_query();
    return $this->load_objects('XParty', $query);
  }
  
  function get_parties_query() {
    return $this->db()->select('parties.id AS id')
                      ->from('parties')
                      ->join('places', 'parties.place_id = places.id')
                      ->where('college_id', $this->id)
                      ->order_by('date', 'ASC')
                      ->order_by('name', 'ASC');
  }
  
  function top_parties() {
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
  
  function parties_on(DateTime $date) {
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
  
  function update_offline_users() {
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
  
  private function _get_open_parties_query($time) {
    //open parties today means parties that occured yesterday
    $time = $this->make_local($time)->modify('-1 day');
    
    return $this->db()
                ->select('parties.id AS id')
                ->from('parties')
                ->where(array(
                  'college_id' => $this->id,
                  'date' => date_format($time, 'Y-m-d'),
                ))
                ->join('places', 'parties.place_id = places.id');
  }
  
}
