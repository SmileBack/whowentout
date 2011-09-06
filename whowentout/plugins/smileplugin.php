<?php

class SmilePlugin
{
  
  /**
   * Occurs when a $sender smiles at $receiver.
   * @param XUser $e->sender
   * @param XUser $e->receiver
   * @param XParty $e->party 
   */
  function on_smile($e) {
    if ($e->sender->was_smiled_at($e->receiver, $e->party)) {
      raise_event('smile_back', array(
        'source' => $e->source,
        'party' => $e->party,
        'sender' => $e->sender,
        'receiver' => $e->receiver,
      ));
    }
    else {
      raise_event('smile_at', array(
        'source' => $e->source,
        'party' => $e->party,
        'sender' => $e->sender,
        'receiver' => $e->receiver,
      ));
    }
  }
  
}
