<p>
  Hi <?= $receiver->first_name ?>,
</p>
<br/>
<p>
  A <?= $sender->gender_word ?> from <?= $party->place->name ?> smiled at you on <?= $date->format('F jS') ?>.
  To see who it might be <?= anchor(site_url("party/$party->id"), 'click here') ?>.
</p>
<br/>
<p>
  -WhoWentOut
</p>