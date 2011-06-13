  <p class="where_did_you_go">Where Did You Go Out Last Night?</p>
  	
	<select name="party">	
		<? foreach ($places as $key => $place): ?>
		
			<option><?php print $place->place_name; ?></option>
		
		<? endforeach; ?>
	</select>
	<input type="submit" />
	Check in by 11pm [ <?php print $timer; ?> ]
	
		
