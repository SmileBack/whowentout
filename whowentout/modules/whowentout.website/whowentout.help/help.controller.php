<?php

class Help extends MY_Controller
{

    function site()
    {
        $this->flag->set('user', current_user()->id, 'has_seen_site_help');
        print r('site_help');
    }

    function smile()
    {
        $this->flag->set('user', current_user()->id, 'has_seen_smile_help');

        $logger = new UserEventLogger();
        $logger->log(current_user(), college()->get_time(), 'smile_help_view');

        print r('smile_help');
    }

    function howitworks()
    {
        $this->require_login();
        enforce_restrictions();
        
        $this->flag->set('user', current_user()->id, 'has_seen_howitworks_help');
        print r('page', array(
                          'page_content' => r('section', array(
                                                           'title' => 'How it Works',
                                                           'body' => r('howitworks_help'),
                                                         )),
                        ));
    }

}
