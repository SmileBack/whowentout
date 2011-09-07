<?php
if (logged_in()) {
    $id = 'user_json_' . current_user()->id;
    $current_user = array();
    $current_user['logged_in'] = logged_in();
    $current_user = array_merge($current_user, current_user()->to_array());
}
?>

<?php if (logged_in()): ?>
<div id="<?= $id ?>" class="serverevents" source="<?= 'user_' . current_user()->id ?>" style="display: none;">
    <?= json_encode($current_user); ?>
</div>
<?php endif; ?>
