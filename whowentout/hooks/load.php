<?php

function load_core()
{
    require_once APPPATH . 'modules/debug/krumo.class.php';
    
    require_once APPPATH . 'libraries/component.php';
    require_once APPPATH . 'libraries/imagerepository.php';
    require_once APPPATH . 'libraries/fb/testfacebook.php';

    require_once APPPATH . 'objects/xobject.php';
    require_once APPPATH . 'objects/xuser.php';
    require_once APPPATH . 'objects/xcollege.php';
    require_once APPPATH . 'objects/xparty.php';
    require_once APPPATH . 'objects/xplace.php';
    require_once APPPATH . 'objects/xsmile.php';
    require_once APPPATH . 'objects/xsmilematch.php';
}
