<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    
    function index()
    {
        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);

        require_once APPPATH . 'core/phpclassparser.class.php';
        require_once APPPATH . 'modules/debug/krumo.class.php';

        $parser = new PHPClassParser();
        $classes = $parser->get_file_classes(APPPATH . 'third_party/pusher.php');
        krumo::dump($classes);
    }

}
