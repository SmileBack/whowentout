<?php

class College_model extends CI_Model {
    
  /**
   * Get all of the parties that the user can check into at $time.
   * @param type $college_id
   * @param DateTime $time
   * @return array
   *   An array of party objects.
   */
  function get_open_parties($college_id, $time) {
    $parties = $this->_get_open_parties_query($college_id, $time)->get()->result();
    return $parties;
  }

  function _get_open_parties_query($college_id, $time) {
    //open parties today means parties that occured yesterday
    $time = make_local($time)->modify('-1 day');
    
    return $this->db
                ->select('parties.id AS id, party_date, places.id AS place_id, place_name, college_id')
                ->from('parties')
                ->where(array(
                  'college_id' => $college_id,
                  'party_date' => date_format($time, 'Y-m-d'),
                ))
                ->join('places', 'parties.place_id = places.id');
  }

}
