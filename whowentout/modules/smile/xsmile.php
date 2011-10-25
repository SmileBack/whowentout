<?php

class XSmile extends XObject
{

    protected static $table = 'smiles';

    function get_sender()
    {
        return XUser::get($this->sender_id);
    }

    function get_receiver()
    {
        return XUser::get($this->receiver_id);
    }

    function get_party()
    {
        return XParty::get($this->party_id);
    }

}
