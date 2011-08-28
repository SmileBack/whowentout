
<p>
  Hi <?= $receiver->first_name ?>,
</p>
<br/>
<p>
  You and <?= $sender->full_name ?> have smiled at each other!
  <?= anchor(site_url("dashboard"), 'click here') ?> to go to your dashboard.
</p>
<br/>
<p>
  -WhoWentOut
</p>