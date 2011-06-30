<p class="where_did_you_go">Where Did You Go Out Last Night?</p> 

<?php if ($has_attended_party): ?>
  You attended <?= anchor("party/$party->id", $party->place_name) ?> last night.
<?php else: ?>
<?= form_open('user/checkin'); ?>
  <?= $parties_dropdown; ?>
  <button type="submit">enter</button>
<?= form_close(); ?>
  <?= $closing_time; ?>
<?php endif; ?>

<?php if ($doors_are_closed): ?>
  <div class="doors_have_closed">
    Doors have closed for checkin.
  </div>
<?php endif; ?>
