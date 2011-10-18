<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $facebook_id = 1148880705;
        $facebook_id = 'grace.hae';

        print '<pre>';
        print_r($this->is_in_georgetown($facebook_id));
        print '</pre>';
    }

    function index2()
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

    function facebook_ids()
    {
        $links = explode("\n", $this->students());
        $ids = array();
        $search = array(
            'http://www.facebook.com/',
            '&ref=pb',
            '?ref=pb',
            'profile.php?id=',
        );
        foreach ($links as $link) {
            $id = trim(str_replace($search, '', $link));
            if (empty($id))
                continue;
            $ids[] = $id;
        }
        return $ids;
    }

    private function get_affiliations($facebook_id)
    {
        $facebook_id = get_facebook_id($facebook_id);
        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "SELECT affiliations FROM user WHERE uid = $facebook_id",
                            ));
        return $result[0]['affiliations'];
    }

    private function is_in_georgetown($facebook_id)
    {
        $georgetown_network_id = '16777231';
        $affiliations = $this->get_affiliations($facebook_id);
        foreach ($affiliations as $cur) {
            if ($cur['nid'] == $georgetown_network_id)
                return TRUE;
        }
        return FALSE;
    }

    private function students()
    {
        return "http://www.facebook.com/kenny.kao1?ref=pb
                http://www.facebook.com/profile.php?id=1232401168&ref=pb
                http://www.facebook.com/cheungcarlos?ref=pb
                http://www.facebook.com/adrian.bautista8?ref=pb
                http://www.facebook.com/profile.php?id=1422161100&ref=pb
                http://www.facebook.com/profile.php?id=100000097963949&ref=pb
                http://www.facebook.com/profile.php?id=1252230429&ref=pb
                http://www.facebook.com/efmalavenda?ref=pb
                http://www.facebook.com/KaliaV91?ref=pb
                http://www.facebook.com/beckykissel?ref=pb
                http://www.facebook.com/profile.php?id=1063440586&ref=pb
                http://www.facebook.com/andrew.butash?ref=pb
                http://www.facebook.com/profile.php?id=1239960152&ref=pb
                http://www.facebook.com/profile.php?id=747930161&ref=pb
                http://www.facebook.com/grace.hae?ref=pb
                http://www.facebook.com/profile.php?id=719875388&ref=pb
                http://www.facebook.com/profile.php?id=635493084&ref=pb
                http://www.facebook.com/profile.php?id=1236748048&ref=pb
                http://www.facebook.com/profile.php?id=804535432&ref=pb
                http://www.facebook.com/profile.php?id=550025898&ref=pb
                http://www.facebook.com/sumegh.sodani?ref=pb
                http://www.facebook.com/lmurad?ref=pb
                http://www.facebook.com/profile.php?id=500476821&ref=pb
                http://www.facebook.com/profile.php?id=536675320&ref=pb
                http://www.facebook.com/profile.php?id=550999502&ref=pb
                http://www.facebook.com/mj.meaney?ref=pb
                http://www.facebook.com/profile.php?id=1373820330&ref=pb
                http://www.facebook.com/regan.page?ref=pb
                http://www.facebook.com/asia.dixon?ref=pb
                http://www.facebook.com/profile.php?id=1338090445&ref=pb
                http://www.facebook.com/profile.php?id=1229100325&ref=pb
                http://www.facebook.com/alma.caballero?ref=pb
                http://www.facebook.com/Jon.Coumes?ref=pb
                http://www.facebook.com/profile.php?id=625050423&ref=pb
                http://www.facebook.com/jkaisamba?ref=pb
                http://www.facebook.com/kat.light1?ref=pb
                http://www.facebook.com/profile.php?id=1341420507&ref=pb
                http://www.facebook.com/profile.php?id=1328167725&ref=pb
                http://www.facebook.com/tim.p.dougherty?ref=pb
                http://www.facebook.com/jcairl?ref=pb
                http://www.facebook.com/will.cousino?ref=pb
                http://www.facebook.com/profile.php?id=1139280149&ref=pb
                http://www.facebook.com/profile.php?id=1288620026&ref=pb
                http://www.facebook.com/profile.php?id=100001449733789&ref=pb
                http://www.facebook.com/maya.chaudhuri?ref=pb
                http://www.facebook.com/CharlieDPOK?ref=pb
                http://www.facebook.com/profile.php?id=565095233&ref=pb
                http://www.facebook.com/profile.php?id=1415730&ref=pb
                http://www.facebook.com/profile.php?id=1332180264&ref=pb
                http://www.facebook.com/profile.php?id=502068658&ref=pb
                http://www.facebook.com/profile.php?id=1470827682&ref=pb
                http://www.facebook.com/profile.php?id=593931914&ref=pb
                http://www.facebook.com/profile.php?id=1658960990&ref=pb
                http://www.facebook.com/sievwrightk?ref=pb
                http://www.facebook.com/kathleen.bushjoseph?ref=pb
                http://www.facebook.com/profile.php?id=1317447583&ref=pb
                http://www.facebook.com/devlin.jack?ref=pb
                http://www.facebook.com/Bonnie.Duncan.17?ref=pb
                http://www.facebook.com/profile.php?id=1120680109&ref=pb
                http://www.facebook.com/profile.php?id=1063590013&ref=pb
                http://www.facebook.com/profile.php?id=100001526510712&ref=pb
                http://www.facebook.com/profile.php?id=541663104&ref=pb
                http://www.facebook.com/shaker.nj?ref=pb
                http://www.facebook.com/kristen.reeve?ref=pb
                http://www.facebook.com/profile.php?id=623934425&ref=pb
                http://www.facebook.com/blhoge?ref=pb
                http://www.facebook.com/dorothy.hector?ref=pb
                http://www.facebook.com/profile.php?id=672517190&ref=pb
                http://www.facebook.com/rachelkellyh?ref=pb
                http://www.facebook.com/profile.php?id=1148880705&ref=pb
                http://www.facebook.com/profile.php?id=641748494&ref=pb
                http://www.facebook.com/profile.php?id=695927233&ref=pb
                http://www.facebook.com/profile.php?id=1361956540&ref=pb
                http://www.facebook.com/profile.php?id=501533296&ref=pb
                http://www.facebook.com/profile.php?id=1003947023&ref=pb
                http://www.facebook.com/profile.php?id=1156650336&ref=pb
                http://www.facebook.com/profile.php?id=746580257&ref=pb
                http://www.facebook.com/profile.php?id=665171346&ref=pb
                http://www.facebook.com/ading?ref=pb
                http://www.facebook.com/profile.php?id=1243200095&ref=pb
                http://www.facebook.com/profile.php?id=1242390122&ref=pb
                http://www.facebook.com/profile.php?id=1111470101&ref=pb
                http://www.facebook.com/annaw828?ref=pb
                http://www.facebook.com/eitanbp?ref=pb
                http://www.facebook.com/alisse.hannaford?ref=pb
                http://www.facebook.com/suczewski?ref=pb
                http://www.facebook.com/schafferd10?ref=pb
                http://www.facebook.com/sarah.radomsky?ref=pb
                http://www.facebook.com/jesse.avila3?ref=pb
                http://www.facebook.com/kelsey.tsai?ref=pb
                http://www.facebook.com/profile.php?id=1409958&ref=pb
                http://www.facebook.com/profile.php?id=1093080100&ref=pb
                http://www.facebook.com/profile.php?id=712606767&ref=pb
                http://www.facebook.com/rtsao?ref=pb
                http://www.facebook.com/john.bufe?ref=pb";
    }

}
