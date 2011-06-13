  <p class="where_did_you_go">Where Did You Go Out Last Night?</p>
  	
	<select name="party">	
		<? foreach ($places as $key => $place): ?>
		
			<option><?php print $place->place_name; ?></option>
		
		<? endforeach; ?>
	</select>
	<input type="submit" />
	Doors close at 11pm [ in <?php print $timer; ?> ]
	
		
