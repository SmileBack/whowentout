<?= form_open('admin/featured_message_save') ?>

<?= form_textarea('featured_message', $featured_message) ?>

<?= form_submit('op', 'save') ?>
<?= form_close() ?>