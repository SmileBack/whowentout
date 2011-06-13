
<p><?= $party->smiles_received; ?> girls have smiled at you :)</p>
<p>You have <?= $party->smiles_remaining; ?> smiles left to give</p>
<? foreach ($party->matches as $match): ?>
<p>You and <?= $match->first_name; ?>  have smiled at each other!</p>
<? endforeach; ?>


<ul class="gallery">

<? foreach ($party_attendees as $key => $attendee): ?>
<li>
	
<?php print img(array(
	'src' => $attendee->profile_pic,
	'width' => $profile_pic_size['width'],
	'height' => $profile_pic_size['height'],
	'alt' => '',
	'class' => '',
));?>

<div class="caption">
	<p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
	<p><?= $attendee->college_name; ?> <?= $attendee->grad_year; ?></p>
	<p>Boca Raton, FL</p>
	<p>Mutual friends: <?= anchor('list_mutual_friends', 8); ?></p>
	<p><input type="button" name="name" value="Smile at <?= $attendee->first_name; ?>" 
		<? if ($attendee->was_smiled_at): ?>disabled="disabled"<? endif; ?>
		/></p> 
</div>

</li>
<? endforeach; ?>

</ul>
