<p><?= anchor('dashboard', 'My Dashboard'); ?></p>
<p><?= anchor('homepage', 'Logout'); ?></p>


<section>
	<h2><?= $party->place_name; ?></h2>
	<h3><?= date("l, F jS", strtotime($party->party_date)); ?></h3>
</section>


<section>
	<p><?= $party->smiles_received; ?> girls have smiled at you :)</p>
	<p>You have <?= $party->smiles_remaining; ?> smiles left to give</p>
	<? foreach ($party->matches as $match): ?>
	<p>You and <?= $match->first_name; ?>  have smiled at each other!</p>
	<? endforeach; ?>
</section>


<section>
	<? foreach ($party_attendees as $key => $attendee): ?>

	<?php print img(array(
		'src' => $attendee->profile_pic,
		'width' => $profile_pic_size['width'],
		'height' => $profile_pic_size['height'],
		'alt' => '',
		'class' => '',
	));?>

	<caption>
		<p><?= $attendee->first_name; ?>, <?= get_age($attendee->date_of_birth) ;?></p>
		<p><?= $attendee->college_name; ?> <?= $attendee->grad_year; ?></p>
		<p>Mutual friends: <?= anchor('list_mutual_friends', 8); ?></p>
		<p><input type="button" name="name" value="Smile at <?= $attendee->first_name; ?>" 
			<? if ($attendee->was_smiled_at): ?>disabled="disabled"<? endif; ?>
			/></p> 
	</caption>

	<? endforeach; ?>
</section>


<section>
	<p>Party Admin: <?= $party->admin_first_name; ?> <?= $party->admin_last_name; ?></p>
</section>


<section>
	<p>Did you see someone last night who isn't here? We can send them an email reminding them to check in!</br>
		(In case you're wondering, your identity will not show up in the email)</p>
	<p>Type their email here:
	<input type="text" name="name" value="Email" />
	<input type="Submit" /></p>
</section>