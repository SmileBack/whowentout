<!doctype html>
<html lang="en" style="height: 100%;">

<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">
    <meta name="viewport" content="width=320px, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">

    <meta property="og:image" content="http://www.whowentout.com/images/facebook_square.2.png" />
    <link rel="image_src" href="/images/facebook_square.2.png" />

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.0000000003.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/jquery.jcrop.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= time() ?>.less"/>

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

<body id="home_page">

    <?php if (browser::is_desktop()): ?>
        <img src="/images/front_page_logo.png?version=31" class="front_page_logo" />
    <?php endif; ?>

    <div class="home_center">

        <?php if (browser::is_desktop()): ?>
            <img src="/images/home_explanation.png?version=31" />
        <?php endif; ?>

        <?php if (browser::is_mobile()): ?>
            <img src="/images/home_mobile_portrait.png?version=31" class="portrait" />
            <img src="/images/home_mobile_landscape.png?version=31" class="landscape" />
        <?php endif; ?>

        <a href="/login" class="login_button">Login with Facebook</a>

    </div>

    <?= js() ?>

    <?php if (environment() == 'whowentout'): ?>
    <?= r::google_analytics() ?>
    <?php endif; ?>

</body>
</html>
