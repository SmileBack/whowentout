  <p class="where_did_you_go">Where Did You Go Out Last Night?</p> 
 	
<?= form_open('ajax/party_attended', '', array('current_time'=>now())); ?>

<?= form_dropdown('party', $places)?>		

<button type="submit">Submit</button>
	
<?= form_close(); ?>
	
	Doors close at 11pm [ in <?php print $timer; ?> ]
		
