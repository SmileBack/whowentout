<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $gallery = new FlickrGallery('72157628067656136');
        
    }

    function index3()
    {
        $db = new Database(array(
                                'host' => 'localhost',
                                'database' => 'whowentout',
                                'username' => 'root',
                                'password' => 'root',
                           ));

        //create a table
        $sql = $db->create_table('facebook_events', array(
                                                             'id' => array(
                                                                 'type' => 'id',
                                                             ),
                                                             'name' => array(
                                                                 'type' => 'string',
                                                             ),
                                                             'start' => array(
                                                                 'type' => 'time',
                                                             ),
                                                             'end' => array(
                                                                 'type' => 'time',
                                                             ),
                                                        ));
    }

    function index2()
    {
        $facebook_id = '776200121';
        //        $events = fb()->api("2614741/events");
        //        krumo::dump($events);
        //        $facebook_id = current_user()->facebook_id;
        //        $result = fb()->api(array(
        //                                 'method' => 'fql.query',
        //                                 'query' => "SELECT name FROM event WHERE eid IN (SELECT eid from event_member WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=$facebook_id) )",
        //                            ));

        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "SELECT uid, name FROM user WHERE uid IN (SELECT uid1 FROM friend WHERE uid2=$facebook_id) AND 'GWU' IN affiliations",
                            ));
        krumo::dump($result);
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
