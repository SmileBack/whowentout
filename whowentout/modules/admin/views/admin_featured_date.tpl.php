<?= form_open('admin/featured_date_save') ?>

<?= form_input('featured_date_string', $featured_date_string) ?>

<?= form_submit('op', 'save') ?>

<?= form_close() ?>