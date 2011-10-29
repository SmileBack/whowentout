<p>
  Hi <?= $receiver->first_name ?>,
</p>
<p>
  A <?= $sender->gender_word ?> from <?= $party->place->name ?> has smiled at you on WhoWentOut.
  You will only find out who it is if you happen to smile at <?= $sender->gender == 'M' ? 'him' : 'her' ?> as well.
  To go to the party gallery, <?= anchor(site_url("party/$party->id"), 'click here') ?>.
</p>
    <br/>
<p>
  -WhoWentOut notifications
</p>