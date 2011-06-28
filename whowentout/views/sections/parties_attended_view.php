
	<ul class="parties_attended">
		
		<? foreach ($parties_attended as $party): ?>						
			<li>
				
				<div class="party_summary">
				<!-- Move to a helper -->                                      
				<div class="date"><?= date("l, F jS", strtotime($party->party_date)); ?></div>
				| <div class="place"><?= anchor("party/{$party->id}", $party->place_name); ?></div>
				
				<!-- TODO: check gender -->
				<div class="smiles">
				  <span class="smiles_received"><?= $party->smile_info['smiles_received']; ?> girls have smiled at you</span>
				  <span class="smiles_remaining"><?php print $party->smile_info['smiles_remaining']; ?> smiles left to give</span>
				  <ul class="matches">
					<? foreach ($party->smile_info['matches'] as $match): ?>
						<li>You and <?= $match->first_name; ?> <?= $match->last_name?> have smiled at each other!</li>
					<? endforeach; ?>
				  </ul>
				</div>
				<!-- TODO: Change to singular if one smile is left -->
				
				

			   </div>
			
			</li>
		<? endforeach; ?>
		</ul>
		

