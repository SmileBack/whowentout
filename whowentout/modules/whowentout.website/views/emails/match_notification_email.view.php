<p>
  Hi <?= $receiver->first_name ?>,
</p>
<p>
  You and <?= $sender->full_name ?> have smiled at each other on WhoWentOut!
  <?= anchor(site_url("dashboard"), 'Click here') ?> to go to your dashboard.
</p>
    <br/>
<p>
  -WhoWentOut notifications
</p>