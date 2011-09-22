
<p>
  Hi <?= $receiver->first_name ?>,
</p>
<p>
  You and <?= $sender->full_name ?> have smiled at each other on WhoWentOut!
  <?= anchor(site_url("dashboard"), 'click here') ?> to go to your dashboard.
</p>
<p style="margin-top: 20px;">
  -WhoWentOut notifications
</p>