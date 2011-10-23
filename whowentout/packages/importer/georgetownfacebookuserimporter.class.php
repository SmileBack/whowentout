<?php

class GeorgetownFacebookUserImporter
{

    private $network_id = '16777231';

    function extract_facebook_ids($links)
    {
        $facebook_ids = $this->extract_all_facebook_ids($links);
        $georgetown_facebook_ids = $this->filter_georgetown_facebook_ids($facebook_ids);
        $this->batch_import_users($georgetown_facebook_ids);
    }

    function batch_import_users($facebook_ids)
    {
        foreach ($facebook_ids as $facebook_id) {
            $this->import_user($facebook_id);
        }
    }

    function import_user($facebook_id)
    {
        $facebook_id = $this->get_facebook_id($facebook_id);
        if ($facebook_id != NULL && !user_exists(array('facebook_id' => $facebook_id)))
            create_user($facebook_id);
    }

    function filter_georgetown_facebook_ids($facebook_ids)
    {
        $georgetown_facebook_ids = array();

        foreach ($facebook_ids as $facebook_id) {
            if ($this->is_valid_georgetown_facebook_id($facebook_id))
                $georgetown_facebook_ids[] = $facebook_id;
        }

        return $georgetown_facebook_ids;
    }

    function is_valid_georgetown_facebook_id($facebook_id)
    {
        try {
            $facebook_id = $this->get_facebook_id($facebook_id);
            if ($this->is_in_georgetown($facebook_id))
                return TRUE;
            else
                return FALSE;
        }
        catch (Exception $e) {
            //error
        }

        return FALSE;
    }

    function extract_all_facebook_ids($links)
    {
        if (is_string($links))
            $links = explode("\n", $links);

        $ids = array();

        foreach ($links as $link) {
            $id = $this->extract_facebook_id($link);
            if (empty($id))
                continue;
            $ids[] = $id;
        }

        return $ids;
    }

    function extract_facebook_id($link)
    {
        $search = array(
            'http://www.facebook.com/',
            '&ref=pb',
            '?ref=pb',
            'profile.php?id=',
        );
        return trim(str_replace($search, '', $link));
    }

    private function get_affiliations($facebook_id)
    {
        $facebook_id = $this->get_facebook_id($facebook_id);
        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "SELECT affiliations FROM user WHERE uid = $facebook_id",
                            ));
        return $result[0]['affiliations'];
    }

    private function is_in_georgetown($facebook_id)
    {
        $affiliations = $this->get_affiliations($facebook_id);
        foreach ($affiliations as $cur) {
            if ($cur['nid'] == $this->network_id)
                return TRUE;
        }
        return FALSE;
    }

    private function get_facebook_id($facebook_id)
    {
        return get_facebook_id($facebook_id);
    }

}
