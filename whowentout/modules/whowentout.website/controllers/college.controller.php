<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class College extends MY_Controller
{

    function students()
    {
        $q = $this->input->get('q');
        $college_id = college()->id;
        $query = $this->db->from('college_students')
                          ->where('college_id', $college_id);

        foreach (preg_split('/\W+/', $q) as $part) {
            $query->like('student_full_name', $part, 'both');
        }
        $results = $query->limit(10)->get()->result();
        
        $matches = array();
        foreach ($results as $result) {
            $matches[] = array('id' => $result->id, 'title' => $result->student_full_name);
        }
        $this->json($matches);
    }
    
}