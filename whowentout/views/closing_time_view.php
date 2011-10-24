<span class="closing_time <?= college()->get_door()->is_open() ? 'doors_open' : 'doors_closed' ?>"
      time="<?= college()->get_door()->get_closing_time()->getTimestamp() ?>">

  <?php if (college()->get_door()->is_open()): ?>
    Doors close for checkin at
    <?= college()->get_door()->get_closing_time()->format('g a') ?>
    [ in <span class="remaining_time"></span> ]
  <?php else: ?>
    Doors have closed for checkin and will reopen at
    <?= college()->get_door()->get_opening_time()->format('g a') ?>
  <?php endif; ?>

</span>
