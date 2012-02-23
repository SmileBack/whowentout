<?php

class TestAction extends Action
{

    function execute()
    {
        apc_store('woo', 'fooo');
    }

}
