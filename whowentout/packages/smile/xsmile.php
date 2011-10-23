<?php

class XSmile extends XObject
{

    protected static $table = 'smiles';

    function get_sender()
    {
        return user($this->sender_id);
    }

    function get_receiver()
    {
        return user($this->receiver_id);
    }

    function get_party()
    {
        return party($this->party_id);
    }

}
