<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
  
  public function index() {
    $repo = new ImageRepository('pics');
    $repo->refresh(1, 'facebook');
    $repo->refresh(1, 'normal');
    $repo->refresh(1, 'thumb');
//    print $repo->exists('facebook', 1) ? 'yea' : 'na';
//    $repo->download('facebook', 1, 'https://graph.facebook.com/8100231/picture?type=large&access_token=161054327279516|8b1446580556993a34880a831ee36856');
//    print "<br>";
//    print $repo->exists('facebook', 1) ? 'yea' : 'na';
    
  }
  
}
