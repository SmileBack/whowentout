<?php

class AdminViewUnsentEmails extends Action
{

    function execute()
    {
        auth()->require_admin();
        print r::admin_page(array(
            'content' => r::admin_unsent_emails(array(

            )),
        ));
    }

}
