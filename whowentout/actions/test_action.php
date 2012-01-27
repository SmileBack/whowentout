<?php

class TestAction extends Action
{
    function execute()
    {
        /* @var $fb Facebook */
        $fb = build('facebook');
        $url = $fb->getLoginUrl();
    }
}
