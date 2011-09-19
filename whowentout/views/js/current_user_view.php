<?php if (logged_in()): ?>
<div id="current_user" class="serverevents"
     channel-id="<?= 'user_' . current_user()->id ?>"
     channel-url="<?= serverchannel_url('user', current_user()->id) ?>"
     style="display: none;">
</div>
<?php endif; ?>
