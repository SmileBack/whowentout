  <p class="where_did_you_go">Where Did You Go Out Last Night?</p> 
 	
<?= form_open('user/checkin', '', array('current_time' => now())); ?>

<?= $parties_dropdown; ?>
  
<button type="submit">enter</button>
	
<?= form_close(); ?>
	
	Doors close at
	<span class="closing_time" time="<?php print $closing_time; ?>"><?php print date('g:i a', $closing_time); ?></span>
	[ in <span class="remaining_time"></span> ]