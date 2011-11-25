<?php

class Party extends Model
{
    
    function venue()
    {
        return $this->related_model('venue');
    }
    
}
