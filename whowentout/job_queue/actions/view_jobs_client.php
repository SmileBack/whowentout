<?php

class ViewJobsClient extends Action
{
    function execute()
    {
        print r::job_client();
    }
}
