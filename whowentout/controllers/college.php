<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class College extends MY_Controller
{

    function students()
    {
        $q = $this->input->get('q');
        $college_id = college()->id;
        $college_id = 1;
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

    function update_offline_users()
    {
        $last_updated = $this->option->get('last_updated_offline_users', 0);
        $time = time();
        $sometime_ago = time() - 10;

        $throttle = $last_updated > $sometime_ago; //updated recently
        
        if (!$throttle) {
            college()->update_offline_users();
            $this->option->set('last_updated_offline_users', $time);
        }
        
        $this->json(array('success' => TRUE, 'throttled' => $throttle));
    }

}
