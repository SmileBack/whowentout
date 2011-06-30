<div class="about">
  <p>WhoWentOut is an online platform that connects people after a night out. You can use WhoWentOut to:
  <ul class=homepage_list>
    <li>See who else was at the same party as you</li>
    <li>Find out which parties were most popular on a particular night</li>
  </ul>
  </p>			
  <p>WhoWentOut is currently open only to students at the <strong>George Washington University.</strong><p>
</div>

<div class="to_get_started">	
  <p>To get started, just say where you went out last night and we'll take care of the rest:</p>
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
      
</div>
	