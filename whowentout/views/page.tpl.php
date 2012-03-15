<!doctype html>
<html lang="en">

<head>
    <?= browser::assign_settings() ?>

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

<?php if (browser::is_desktop()): ?>
<div id="right">
</div>
<?php endif; ?>

<!-- page end -->

<?= flash::message() ?>

<script type="text/javascript">
    $(function() {
        <?= js()->to_js(); ?>
    });
</script>

<?php if (environment() == 'localhost' || environment() == 'whowasout'): ?>
<?= r::debug_summary() ?>
<?php endif; ?>

<?php if (browser::is_desktop()): ?>
<?= r::feedback() ?>
<?php endif; ?>

<?php if (environment() == 'whowentout'): ?>
<?= r::google_analytics() ?>
<?php endif; ?>

<div id="view"></div>

</body>
</html>
