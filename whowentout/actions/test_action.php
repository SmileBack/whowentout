<?php

class TestAction extends Action
{

    function execute()
    {
        $curl = new Curl();

        /* Jennifer Abrams
        facebook id
        685410025*/

        $response = $curl->get('http://xvc5.showoff.io/link', array(
            'name' => 'Jennifer Abrams',
            'facebook_id' => '685410025',
        ));
        $response = json_decode($response->body);

        krumo::dump($response);
    }

}
