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

    <link rel="stylesheet/less" type="text/css" media="only screen and (max-device-width: 480px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>

    <!--<link rel="stylesheet/less" type="text/css" media="screen and (max-width: 800px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>-->

    <script src="/js/less.js" type="text/javascript"></script>
    <script src="/js/head.load.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/js/underscore.js"></script>

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/backbone.js"></script>

    <script type="text/javascript" src="/js/jquery.class.js"></script>
    <script type="text/javascript" src="/js/jquery.entwine.js"></script>
    <script type="text/javascript" src="/js/jquery.position.js"></script>
    <script type="text/javascript" src="/js/jquery.body.js"></script>
    <script type="text/javascript" src="/js/jquery.dialog.js"></script>

    <script type="text/javascript" src="/js/page.0000000025.js"></script>
</head>

<body id="home_page">

    <img src="/images/home.png?version=5" />

    <a href="/login" class="login_button">Login with Facebook</a>

    <?= js() ?>
</body>
</html>
