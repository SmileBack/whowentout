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
   *   An array of party objects.
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
  
  /**
   * Gives you the date for today at current college (12am).
   * @param bool $local
   * @return DateTime
   */
  function today($local = FALSE) {
    $current_local_time = $this->current_time(TRUE);
    $current_local_time->setTime(0, 0, 0);
    return $local ? $this->make_local($current_local_time) 
                  : $this->make_gmt($current_local_time);
  }
  
  function yesterday($local = FALSE) {
    $current_local_time = $this->current_time(TRUE);
    $current_local_time->setTime(0, 0, 0);
    $current_local_time->modify('-1 day');
    return $local ? $this->make_local($current_local_time)
                  : $this->make_gmt($current_local_time);
  }
  
  function tomorrow($local = FALSE) {
    $current_local_time = $this->current_time(TRUE);
    $current_local_time->setTime(0, 0, 0);
    $current_local_time->modify('+1 day');
    return $local ? $this->make_local($current_local_time)
                  : $this->make_gmt($current_local_time);
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
    $query = $this->db()->select('id')
                        ->from('places')
                        ->where('college_id', $this->id)
                        ->order_by('name', 'ASC');
    return $this->load_objects('XPlace', $query);
  }
  
  function get_parties() {
    $query = $this->db()->select('parties.id AS id')
                        ->from('parties')
                        ->join('places', 'parties.place_id = places.id')
                        ->where('college_id', $this->id)
                        ->order_by('date', 'ASC')
                        ->order_by('name', 'ASC');
    return $this->load_objects('XParty', $query);
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
