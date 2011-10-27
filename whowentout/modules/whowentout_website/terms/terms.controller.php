<?php

class Terms extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->load_section_view('Terms', 'terms');
    }

    function about_us()
    {
        $this->load_section_view('About Us', 'about_us');
    }

    function faq()
    {
        $this->load_section_view('FAQ', 'faq');
    }

    protected function load_section_view($title, $subview_name)
    {
        print r('page', array(
                             'page_content' => r('section', array(
                                                                 'title' => $title,
                                                                 'body' => r($subview_name),
                                                            ))
                        ));
    }

}
