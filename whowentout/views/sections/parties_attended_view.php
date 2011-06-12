
	<ul>
		<li>
			Last night
			<select name="party">	
				<? foreach ($places as $key => $place): ?>
					<option><?php print $place->place_name; ?></option>
				<? endforeach; ?>
			</select>
			<button type="submit">Enter</button>
			Doors close in <?php print $timer; ?></td>
		</li>	
		
		<? foreach ($parties_attended as $party): ?>						
			<li>
				<!-- Move to a helper -->                                      
				<span><?= date("l, F jS", strtotime($party->party_date)); ?></span>
				<span><?= anchor("party/{$party->id}", $party->place_name); ?></span>
				
				<!-- TODO: check gender -->
				<span><?= $party->smiles_received; ?> girls have smiled at you</span>
				<!-- TODO: Change to singular if one smile is left -->
				<span><?php print $party->smiles_remaining; ?> smiles left to give</span>
				
				<ul>
					<? foreach ($party->matches as $match): ?>
						<li>You and <?= $match->first_name; ?> <?= $match->last_name?> have smiled at each other!</li>
					<? endforeach; ?>
				</ul>
			</li>
		<? endforeach; ?>
		</ul>
		<p><em>*you can check in to a maximum of 3 parties per week</em></p>

