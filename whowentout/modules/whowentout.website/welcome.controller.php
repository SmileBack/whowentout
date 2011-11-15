<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $db = new Database(array(
                                'host' => 'localhost',
                                'database' => 'test',
                                'username' => 'root',
                                'password' => 'root',
                           ));

//        $db->create_table('groups', array(
//                                      'id' => array('type' => 'id'),
//                                      'name' => array('type' => 'string'),
//                                    ));
//
//        $db->table('groups')->add_column('hello', array('type' => 'time'));
//
//        $db->destroy_table('groups');


        $user_row = $db->table('users')->row(1);
        $user_row->last_edit = $user_row->last_edit->modify('+1 day');
        $user_row->save();
    }

    function test($name)
    {
        $emails = array('ven' => 'vendiddy@gmail.com',
                        'berenholtzdan@gmail.com',
                        'ventxt' => '4438569502@txt.att.net');

        if (isset($emails[$name])) {
            $email = $emails[$name];
            job_call_async('send_email', $email, 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
            print "<h3>sent email to $email</h3>";
        }
        else {
            print "<h3>no such email</h3>";
        }
    }

}
