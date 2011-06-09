<p><?php print anchor('homepage', 'Logout'); ?></p>

<h3>Welcome, <?= $user->first_name?>!</h3>

<section>
	<p><strong>My Info</strong></p>
	
	<p><?= anchor('edit_info', 'edit info'); ?></p>
		<?= img(
		array(
		'src' => $user->profile_pic,
		'width' => '142',
		'height' => '196',
		'alt' => '',
		'class' => '',
		)
	);
	?>

	<caption class="caption">
		<p><?= $user->first_name ?> <?= $user->last_name ?>, <?= get_age($user->date_of_birth) ?></p>
		<p><?= $user->college_name ?> <?= $user->grad_year ?></p>
	</caption>
</section>


<section>
	<strong>Parties I've attended</strong>
	<ul>
		<li>
			Last night
			<select name="party">	
				<? foreach ($places as $key => $place): ?>
					<option><?php print $place->place_name; ?></option>
				<? endforeach; ?>
			</select>
			<input type="submit"/>
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
				<span>You have <?php print $party->smiles_remaining; ?> smiles left to give</span>
				
				<ul>
					<? foreach ($party->matches as $match): ?>
						<li>You and <?= $match->first_name; ?> <?= $match->last_name?> have smiled at each other!</li>
					<? endforeach; ?>
				</ul>
			</li>
		<? endforeach; ?>
		</ul>
		<p><em>*you can check in to a maximum of 3 parties per week</em></p>
</section>


<section>
	<p><strong>Top parties from last night</strong>
	<p>This is a list of last night's most popular parties. The list will be continually updated throughout the day as more people check in, and will be finalized at midnight.</p>
	<ol>	
		<li>...</li>
		<li>...</li>
		<li>...</li>
	</ol>
	<p>You can click <?= anchor ('top_parties', 'here')?> to see top parties from past nights.</p>
</section>