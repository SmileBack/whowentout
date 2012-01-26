<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="description" content=""/>
        <meta name="author" content="">
        <meta name="viewport" content="width=320px, initial-scale=1, maximum-scale=1">
        <meta name="apple-mobile-web-app-capable" content="yes" />

        <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= filemtime('./css/styles.less') ?>.less"/>
        <link rel="stylesheet/less" type="text/css" href="/css/styles.mobile.<?= filemtime('./css/styles.mobile.less') ?>.less"/>

        <script src="/js/less.js" type="text/javascript"></script>

        <?php
            /* @var $asset Asset */
            $asset = build('asset');
            $asset->load_js('mobile_deal_page.js');
        ?>
        <?= $asset->scripts() ?>

    </head>

    <body class="<?= browser::classes() ?>">
        <?= r::deal_popup(array('user' => $user, 'event' => $event)); ?>
    </body>

</html>
