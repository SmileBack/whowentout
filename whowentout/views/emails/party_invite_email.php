<p>Hi <?= first_name($full_name) ?>,</p>
<p>
    Someone who was with you at <?= $party->place->name ?> last night wants you to check in on WhoWentOut.
    <a href="<?= site_url() ?>">Click here</a> to check in and see who else was there.
</p>
    <br/>
<p>
  -WhoWentOut notifications
</p>