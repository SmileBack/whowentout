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
    <script type="text/javascript" src="/js/backbone.js"></script>

    <script type="text/javascript" src="/js/jquery.class.js"></script>
    <script type="text/javascript" src="/js/jquery.entwine.js"></script>
    <script type="text/javascript" src="/js/jquery.position.js"></script>
    <script type="text/javascript" src="/js/jquery.body.js"></script>
    <script type="text/javascript" src="/js/jquery.dialog.js"></script>

    <script type="text/javascript" src="/js/page.0000000025.js"></script>
</head>

<body id="home_page">

    <img src="/images/home.png?version=4" />

    <a href="/login" class="login_button">Login with Facebook</a>

    <?= js() ?>
</body>
</html>
