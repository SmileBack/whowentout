<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">
    <meta name="viewport" content="width=320px, initial-scale=1, maximum-scale=1">


    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/jquery.jcrop.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= filemtime('./css/styles.less') ?>.less"/>

    <!--<link rel="stylesheet/less" type="text/css" media="only screen and (max-device-width: 480px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>-->

    <link rel="stylesheet/less" type="text/css" media="screen and (max-width: 800px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>

    <script src="/js/less.js" type="text/javascript"></script>

    <?php
        /* @var $asset Asset */
        $asset = build('asset');
        $asset->load_js('page.js');
    ?>

    <?= $asset->scripts() ?>

    <?php if (false): ?>
        <script type="text/javascript" src="http://js.pusher.com/1.11/pusher.js"></script>
        <script type="text/javascript" src="/js/scriptsharp/mscorlib.debug.js"></script>
        <script type="text/javascript" src="/js/scriptsharp/WebUI.debug.<?= filemtime('./js/scriptsharp/WebUI.debug.js') ?>.js"></script>
        <script type="text/javascript" src="/js/scriptsharp/whowentout.debug.<?= filemtime('./js/scriptsharp/whowentout.debug.js') ?>.js"></script>
    <?php endif; ?>
</head>

<body>

<nav id="nav">

    <a class="logo" href="/"><img src="/images/logo.transparent.png"/></a>

    <?= a('colors', 'change colors', array('class' => 'show_color_option_link')) ?>

    <div class="tabs">

        <?= a('today', 'Events', array('class' => 'events_link')) ?>

        <?php if (auth()->logged_in()): ?>
        <?= a('profile/' . auth()->current_user()->id, 'My Profile', array('class' => 'profile_link')) ?>
        <?php endif; ?>

        <?php if (auth()->is_admin()): ?>
        <?= a('admin', 'Admin', array('class' => 'admin_link')) ?>
        <?php endif; ?>

        <?= auth()->get_login_link() ?>
    </div>

</nav>

<div id="page">

    <div id="content">
        <?= $content ?>
    </div>

</div>
<!-- page end -->

<?= flash::message() ?>

<?= js() ?>

</body>
</html>
