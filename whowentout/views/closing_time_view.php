<span class="closing_time <?= college()->doors_are_closed() ? 'doors_closed' : 'doors_open' ?>"
      time="<?= date_format(college()->get_closing_time(TRUE), 'U') ?>">
  
  <?php if ( college()->doors_are_closed() ): ?>
    Doors have closed for checkin and will reopen at
    <?= date_format(college()->get_opening_time(TRUE), 'g a') ?>
  <?php else: ?>
    Doors close at
    <?= date_format(college()->get_closing_time(TRUE), 'g a') ?>
    [ in <span class="remaining_time"></span> ]
  <?php endif; ?>
    
</span>
