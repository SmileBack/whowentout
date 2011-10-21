<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index2()
    {
        $party = party(32);

        $ven = user(array('first_name' => 'Venkat'));
        $dan = user(array('last_name' => 'Berenholtz'));

        $maggie = user(96);
        $claire = user(82);
        $jenny = user(108);
        $allie = user(184);

        require_once APPPATH . 'classes/index.class.php';
        
        $idx = new DirectoryIndex(APPPATH . 'libraries', $this->cache);
        $idx->rebuild();

        krumo::dump($idx->data());
    }

}
