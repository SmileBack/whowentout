<?php

class College_model extends CI_Model {

    function get_parties($college_id, $date) {
        $parties = $this->_get_parties_query($college_id, $date)->get()->result();
        return $parties;
    }

    function _get_parties_query($college_id, $date) {
        return $this->db
                    ->select('parties.id AS id, party_date, places.id AS place_id, place_name, college_id')
                    ->from('parties')
                    ->where(array(
                      'college_id' => $college_id,
                      'party_date' => date('Y-m-d', $date),
                    ))
                    ->join('places', 'parties.place_id = places.id');
    }

}
