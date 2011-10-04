<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{


    function index()
    {
        $this->load->library('chat');

        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $remi = user(97);
        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);

        $js = array('lib/jquery.js',
                    'lib/underscore.js',
                    'lib/jquery.jstorage.js',
                    'lib/jquery.idle-timer.js',
                    'lib/jquery.form.js',
                    'lib/jquery.entwine.js',
                    'lib/jquery.class.js',
                    'lib/jquery.ext.js');

        $force = $this->input->get('force') == 'true' ? TRUE : FALSE;

        print 'updating';
        $result = current_user()->update_friends_from_facebook($force);
        var_dump($result);
        print 'done!';
    }

    function async_update()
    {

        job_call_async('update_facebook_friends', current_user()->id);
        print 'updating async.';
    }

}
