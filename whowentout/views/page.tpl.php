<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/jquery.jcrop.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= time() ?>.less"/>

    <script src="/js/less.js" type="text/javascript"></script>
    <script src="/js/head.load.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/underscore.js"></script>

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.class.js"></script>
    <script type="text/javascript" src="/js/jquery.entwine.js"></script>
    <script type="text/javascript" src="/js/jquery.position.js"></script>
    <script type="text/javascript" src="/js/jquery.body.js"></script>
    <script type="text/javascript" src="/js/jquery.dialog.js"></script>
    <script type="text/javascript" src="/js/jquery.maskedinput.js"></script>

    <script type="text/javascript" src="/js/jquery.jcrop.js"></script>

    <script type="text/javascript" src="/js/page.0000000013.js"></script>
</head>

<body>

<nav id="nav">

    <a class="logo" href="/"><img src="/images/logo.png"/></a>

    <div class="tabs">
        <?= a('events', 'Events') ?>

        <?php if (auth()->logged_in()): ?>
        <?= a('profile/view', 'My Profile') ?>
        <?php endif; ?>

        <?php if (auth()->is_admin()): ?>
        <?= a('admin', 'Admin') ?>
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
