<?php

class TestFacebookNetworkPlugin
{

    function on_call_facebook_api($e)
    {
        if (is_array($e->options)) {
            if ($e->options['method'] == 'fql.query') {
                if ($e->options['query'] == 'SELECT affiliations FROM user WHERE uid = 776200121') {
                    $e->response = $this->get_776200121_affiliations();
                }
                elseif ($e->options['query'] == 'SELECT affiliations FROM user WHERE uid = 100001981675908') {
                    $e->response = $this->get_100001981675908_affiliations();
                }

                if (string_starts_with('SELECT affiliations FROM user WHERE uid = ', $e->options['query'])) {
                    $e->response = $e->default_response;
                    $e->response[0]['affiliations'][] = array(
                        'nid' => '16777231',
                        'name' => 'Georgetown University',
                        'type' => 'college',
                    );
                }
                
            }
        }
        elseif (is_string($e->options)) {
            if ($e->options == "/776200121") {
                $e->response = $e->default_response;
                $this->modify_776200121_api($e->response);
            }
            elseif ($e->options == "/100001981675908") {
                $e->response = $e->default_response;
                $this->modify_100001981675908_api($e->response);
            }
        }

        
    }

    function get_100001981675908_affiliations()
    {
        return $this->get_776200121_affiliations();
    }

    function modify_100001981675908_api(&$data)
    {
        $this->modify_776200121_api($data);
    }

    function modify_776200121_api(&$data)
    {
        $data['education'][] = array(
            'school' =>
            array(
                'id' => '108727889151725',
                'name' => 'George Washington University',
            ),
            'year' =>
            array(
                'id' => '201638419856163',
                'name' => '2012',
            ),
            'type' => 'College',
        );
    }

    function get_776200121_affiliations()
    {
        return array(
            0 =>
            array(
                'affiliations' =>
                array(
                    0 =>
                    array(
                        'nid' => '16777274',
                        'name' => 'Maryland',
                        'type' => 'college',
                    ),
                    array(
                        'nid' => '16777270',
                        'name' => 'GWU',
                        'type' => 'college',
                    ),
                    2 =>
                    array(
                        'nid' => '16777219',
                        'name' => 'Stanford',
                        'type' => 'college',
                    ),
                ),
            ),
        );
    }
}
