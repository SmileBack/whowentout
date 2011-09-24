<script src="http://js.pusherapp.com/1.9/pusher.min.js" type="text/javascript"></script>

<?= js_asset('lib/date.format.js') ?>
<?= js_asset('lib/timeinterval.js') ?>
<?= js_asset('lib/json.js') ?>

<?= js_asset('lib/soundmanager2-nodebug.js') ?>
<?= js_asset('lib/soundmanager2.config.js') ?>

<?= js_asset('lib/jquery.js') ?>
<?= js_asset('lib/underscore.js') ?>
<?= js_asset('lib/jquery.jstorage.js') ?>
<?= js_asset('lib/jquery.idle-timer.js') ?>
<?= js_asset('lib/jquery.form.js') ?>
<?= js_asset('lib/jquery.entwine.js') ?>
<?= js_asset('lib/jquery.class.js') ?>

<?= js_asset('lib/jquery.body.js') ?>
<?= js_asset('lib/jquery.position.js') ?>
<?= js_asset('lib/jquery.jcrop.js') ?>

<?= js_asset('lib/jquery.spotlight.js') ?>

<?= js_asset('whowentout.component.js') ?>
<?= js_asset('whowentout.hash.js') ?>
<?= js_asset('whowentout.model.js') ?>
<?= js_asset('whowentout.channel.js') ?>
<?= js_asset('whowentout.college.js') ?>
<?= js_asset('whowentout.place.js') ?>
<?= js_asset('whowentout.party.js') ?>
<?= js_asset('whowentout.user.js') ?>
<?= js_asset('whowentout.application.js') ?>

<!--[if IE]>
<?= js_asset('ie.js') ?>
<![endif]-->

<?= js_asset('widgets/jquery.autocomplete.js') ?>
<?= js_asset('widgets/jquery.dialog.js') ?>
<?= js_asset('widgets/jquery.notifications.js') ?>
<?= js_asset('widgets/chatbar.js') ?>

<?= js_asset('core.js') ?>
<?= js_asset('time.js') ?>

<?= js_asset('pages/home.js') ?>
<?= js_asset('pages/dashboard.js') ?>
<?= js_asset('pages/gallery.js') ?>
<?= js_asset('pages/editinfo.js') ?>

<?= js_asset('script.js') ?>

<?= js_asset('lib/jsaction.js') ?>
<?= js_asset('actions.js') ?>
<?php if (isset($this->jsaction)): ?>
    <?= $this->jsaction->run() ?>
<?php endif; ?>