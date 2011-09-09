<?php
if (logged_in()) {
    $id = 'current_user';
    $current_user = array();
    $current_user = array_merge($current_user, current_user()->to_array(TRUE));
}
?>

<?php if (logged_in()): ?>
<div id="<?= $id ?>" class="serverevents"
     channel-id="<?= 'user_' . current_user()->id ?>"
     channel-url="<?= serverchannel_url('user', current_user()->id) ?>"
     style="display: none;">
    <?= json_encode($current_user); ?>
</div>
<?php endif; ?>
