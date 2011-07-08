Doors close at
  
<span class="closing_time" time="<?= date_format(get_closing_time(TRUE), 'U') ?>">
  <?= date_format(get_closing_time(TRUE), 'g a'); ?>
  [ in <span class="remaining_time" remaining="<?= get_seconds_until_close() ?>"></span> ]
</span>
