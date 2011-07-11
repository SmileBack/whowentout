<?php

class XCollege extends XObject
{
  
  protected static $table = 'colleges';
  
  static function current() {
    return XCollege::get( 1 );
  }
  
  function add_party($date, $place_id) {
    $party = XParty::create(array(
      'place_id' => $place_id,
      'date' => $date,
    ));
    return $party;
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
  
  function get_students() {
    $query = $this->db()->select('id')
                        ->from('users')
                        ->where('college_id', $this->id)
                        ->order_by('first_name', 'ASC');
    return $this->load_objects('XUSer', $query);
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
    $time = yesterday(TRUE);
    
    $sql = "SELECT party_id AS id, party_date, LEAST(males, females) AS score FROM
            (
              SELECT party_id, parties.date AS party_date, SUM(gender = 'M') AS males, SUM(gender = 'F') as females
              FROM party_attendees
                INNER JOIN parties ON party_attendees.party_id = parties.id
                INNER JOIN users ON party_attendees.user_id = users.id
              WHERE parties.date = ?    
              GROUP BY party_id
            ) AS party_counts
            ORDER BY score DESC";
    
    $query = $this->db()->query($sql, array(date_format($time, 'Y-m-d')));
    
    return $this->load_objects('XParty', $query);
  }
  
  private function _get_open_parties_query($time) {
    //open parties today means parties that occured yesterday
    $time = make_local($time)->modify('-1 day');
    
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

