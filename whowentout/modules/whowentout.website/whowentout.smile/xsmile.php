<?php

class XSmile extends XObject
{

    protected static $table = 'smiles';

    function get_match()
    {
        $query = $this->db()->select('id')
                            ->from('smile_matches')
                            ->where('first_smile_id', $this->id)
                            ->or_where('second_smile_id', $this->id)->get();
        return XObject::load_first('XSmileMatch', $query);
    }

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
