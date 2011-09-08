<?php

class College extends MY_Controller
{

    function students()
    {
        $q = $this->input->get('q');
        $college_id = college()->id;
        $results = ci()->db->from('college_students')
                ->where('college_id', $college_id)
                ->like('student_full_name', $q, 'both')
                ->limit(10)
                ->get()->result();

        $matches = array();
        foreach ($results as $result) {
            $matches[] = array('id' => $result->id, 'title' => $result->student_full_name);
        }
        print json_encode($matches);
        exit;
    }

    function update_offline_users()
    {
        $last_updated = $this->option->get('last_updated_offline_users', 0);
        $sometime_ago = current_time()->modify('-10 seconds')->getTimestamp();
        $throttle = $last_updated > $sometime_ago; //updated recently
        
        if (!$throttle) {
            college()->update_offline_users();
            $this->option->set('last_updated_offline_users', current_time()->getTimestamp());
        }
        
        $this->json(array('success' => TRUE, 'throttled' => $throttle));
    }

}
