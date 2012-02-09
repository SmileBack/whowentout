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
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.0000000003.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/jquery.jcrop.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= time() ?>.less"/>

    <link rel="stylesheet/less" type="text/css" media="only screen and (max-device-width: 480px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>

    <!--<link rel="stylesheet/less" type="text/css" media="screen and (max-width: 800px)"
          href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>-->

    <script src="/js/less-1.2.1.js" type="text/javascript"></script>

    <?php
        /* @var $asset Asset */
        $asset = build('asset');
        $asset->load_js('page.js');
    ?>
    <?= $asset->scripts() ?>
</head>

<body id="home_page">

    <div class="home_center">
        <img src="/images/home.png?version=10" />
        <a href="/login" class="login_button">Login with Facebook</a>
    </div>

    <?= js() ?>
</body>
</html>
