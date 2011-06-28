<?= load_section_view('gallery_view', date("l, F jS", strtotime($party->party_date)) . ' | ' . $party->place_name); ?>

<section id="email_section">
	<p>Did you see someone last night who isn't here? We can send them an email reminding them to check in!</br>
		(In case you're wondering, your identity will not show up in the email)</p>
	<p>Type their email here:
	<input type="text" name="name" value="Email" />
	<input type="Submit" /></p>
</section>