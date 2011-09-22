<p>
  Hi <?= $receiver->first_name ?>,
</p>
<p>
  A <?= $sender->gender_word ?> from <?= $party->place->name ?> has smiled at you on WhoWentOut.
  To see who it might be, <?= anchor(site_url("party/$party->id"), 'click here') ?>.
</p>
<p style="margin-top: 20px;">
  -WhoWentOut notifications
</p>