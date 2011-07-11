<span class="closing_time <?= doors_are_closed() ? 'doors_closed' : 'doors_open' ?>" time="<?= date_format(get_closing_time(TRUE), 'U') ?>">
  <?php if ( doors_are_closed() ): ?>
    Doors have closed for checkin and will reopen at 1 am
  <?php else: ?>
    Doors close at <?= date_format(get_closing_time(TRUE), 'g a'); ?>
    [ in <span class="remaining_time" remaining="<?= get_seconds_until_close() ?>"></span> ]
  <?php endif; ?>
</span>
