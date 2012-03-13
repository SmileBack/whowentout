<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">
    <meta name="viewport" content="width=320px, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <meta property="og:image" content="http://www.whowentout.com/images/facebook_square.2.png" />
    <link rel="image_src" href="/images/facebook_square.2.png" />

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.<?= filemtime('./css/dialog.less') ?>.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/jquery.jcrop.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= filemtime('./css/styles.less') ?>.less"/>

    <?php if (browser::is_mobile()): ?>
    <link rel="stylesheet/less" type="text/css"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>
    <?php endif; ?>

    <script src="/js/less-1.2.1.js" type="text/javascript"></script>

    <?php
    /* @var $asset Asset */
    $asset = build('asset');
    $asset->load_js('page.js');
    ?>

    <?= $asset->scripts() ?>
</head>

<body class="<?= browser::classes() ?>">

<script id="date-template" type="text/x-handlebars-template">
    <h3>{{formatDate date}}</h3>
</script>

<script id="side-profile-template" type="text/x-handlebars-template">
    <div class="side_profile">

        <h3 class="date">{{formatDate date}}</h3>

        <div class="profile_small">
            <div class="gallery_thumb">
                <img src="http://localhost/pics/12101.thumb.jpg?version=8" />
            </div>
            <div class="profile_name">{{user.first_name}} {{user.last_name}}</div>
        </div>

        {{#if event}}
        <div class="event_selection_summary">
            <h3>Going to</h3>
            <div class="going_to">{{event.name}}</div>
            <ul>
                <li><a class="switch" href="#switch">switch</a></li>
                <li><a class="action show_deal_link " href="/events/{{event.id}}/deal">claim deal</a></li>
                <li><a class="action event_invite_link" href="/events/{{event.id}}/invite">invite</a></li>
            </ul>
        </div>
        {{/if}}
    </div>
</script>

<nav id="nav">

    <a class="logo" href="/"><img src="/images/logo.transparent.png" class="logo_transparent"/></a>

    <div class="tabs">

        <?= a('day', 'Parties', array('class' => 'events_link')) ?>

        <?= r::profile_tab(); ?>

        <?= r::entourage_tab(); ?>

        <?php if (auth()->is_admin()): ?>
        <?= a('admin', 'Admin', array('class' => 'admin_link')) ?>
        <?php endif; ?>

        <?php if (browser::is_desktop()): ?>
        <?= auth()->get_login_link() ?>
        <?php endif; ?>
    </div>

</nav>

<div id="page">

    <div id="content">
        <?= $content ?>
    </div>

    <div id="footer">
        <?= a('terms', 'Terms') ?>
        <?= a('mission', 'Mission') ?>
        <?php if (browser::is_mobile()): ?>
            <?= auth()->get_login_link(); ?>
        <?php endif; ?>
    </div>

</div>

<div id="right">

</div>

<!-- page end -->

<?= flash::message() ?>

<?= js() ?>

<?php if (environment() == 'localhost' || environment() == 'whowasout'): ?>
<?= r::debug_summary() ?>
<?php endif; ?>

<?php if (browser::is_desktop()): ?>
<?= r::feedback() ?>
<?php endif; ?>

<?php if (environment() == 'whowentout'): ?>
<?= r::google_analytics() ?>
<?php endif; ?>

</body>
</html>
