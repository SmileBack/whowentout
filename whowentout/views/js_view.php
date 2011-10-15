<script src="http://js.pusherapp.com/1.9/pusher.js" type="text/javascript"></script>

<?php
$this->asset->load(array(
                        'whowentout.application.js',
                        'widgets/jquery.autocomplete.js',
                        'widgets/jquery.dialog.js',
                        'widgets/jquery.notifications.js',
                        'widgets/chatbar.js',

                        'core.js',
                        'time.js',

                        'pages/editinfo.js',
                        'pages/home.js',
                        'pages/dashboard.js',
                        'pages/gallery.js',
                        'pages/editinfo.js',
                        'pages/friends.js',

                        'script.js',

                        'lib/jsaction.js',
                        'actions.js',
                   ));
?>

<?= $this->asset->js() ?>

<!--[if IE]>
<?= js_asset('ie.js') ?>
<![endif]-->

<?php if (isset($this->jsaction)): ?>
    <?= $this->jsaction->run() ?>
<?php endif; ?>