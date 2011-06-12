
<p>WhoWentOut is an online platform that connects people after a night out. You can use WhoWentOut to:
<ul>
	<li>See who else was at the same party as you</li>
	<li>Find out which parties were most popular on a particular night</li>
</ul>
</p>			
<p>WhoWentOut is currently open only to students at the <strong>George Washington University.</strong><p>

-----
	
<p>To get started, just say where you went out last night and we'll take care of the rest:</p>
<p id="where-did-you-go"><strong>Where Did You Go Out Last Night?</strong></p>
<form>
	<select name="party">	
	<?php foreach ($places as $key => $place): ?>

		<option><?php print $place->place_name ?></option>
	
	<?php endforeach; ?>
	</select>
	<input type="submit"/>

	Doors close in <?php print $timer; ?>
</form>
	