<div class="profile">
    <div class="profile_inner">

        <div class="profile_main">
            
			<h2 class="profile_name"><?= $user->first_name . ' ' . $user->last_name ?></h2>
			
			<div class="profile_top">
				<div class="profile_pic">
	                <?= img($profile_picture_url) ?>

	                <?php if ($your_profile && browser::is_desktop()): ?>
	                <a title="Edit Profile" href="/profile/picture/edit" class="action profile_edit_picture_link">Edit Pic</a>
	                <?php endif; ?>
	            </div>

	            <div class="profile_info"> 
	                <dl>
	                    <dt>Colleges</dt>
	                    <dd>
	                        <ul class="profile_networks">
	                            <?php foreach ($user->networks->where('type', 'college') as $network): ?>
	                            <li><?= $network->name ?></li>
	                            <?php endforeach; ?>
	                        </ul>
	                    </dd>

	                    <dt>Hometown</dt>
	                    <dd><?= $user->hometown ?></dd>
	                </dl>
	            </div>

				<?php if (!$your_profile): ?>
		        <section class="mutual_friends_section">
		            <h3>Mutual Friends (<?= count($mutual_friends) ?>)</h3>
		            <?php if (count($mutual_friends) > 0): ?>
		                <?= r::profile_gallery(array('users' => $mutual_friends, 'preset' => 'facebook.normal', 'show_networks' => false)); ?>
		            <?php else: ?>
		                <h2>You and <?= $user->first_name ?> have no friends in common.</h2>
		            <?php endif; ?>
		        </section>
		        <?php endif; ?>

	            <?php if (!$your_profile): ?>
	            <a class="send_message_link coming_soon" href="#send_message">Send Message</a>
	            <?php endif; ?>
			</div>
        </div>

        <section class="checkins_section">
            <h3>
                <?= ucfirst(format::owner($user)) ?> Checkins
            </h3>
            <?= r::profile_checkins(array('user' => $user)); ?>
        </section>

		<?= r::entourage_section(array('user' => $user, 'show_invite_link' => $your_profile)); ?>


    </div>
</div>
