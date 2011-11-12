<?= form_open('admin/featured_date_save') ?>

<fieldset>
    <?php foreach ($featured_date_strings as $date_string): ?>
    <div>
        <?= form_input('featured_date_strings[]', $date_string) ?>
    </div>
    <?php endforeach; ?>
</fieldset>
    
<?= form_submit('op', 'save') ?>

<?= form_close() ?>