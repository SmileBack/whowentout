<?php

class XCollege extends XObject
{
  
  protected static $table = 'colleges';
  
  static function current() {
    return XCollege::get( 1 );
  }
  
  /**
   * Get all of the parties that the user can check into at $time.
   * @param DateTime $time
   * @return array
   *   An array of party objects.
   */
  function open_parties($time) {
    $parties = array();
    $rows = $this->_get_open_parties_query($time)->get()->result();
    foreach ($rows as $row) {
      $parties[] = XParty::get( $row->id );
    }
    return $parties;
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

