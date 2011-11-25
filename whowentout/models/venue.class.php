<?php

class Venue extends Model
{
    
    function parties()
    {
        return $this->related_model_collection('party');
    }
    
}
