<!doctype html>
<html lang="en" style="height: 100%;">

<head>

    <meta charset="utf-8">
    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/admin_styles.<?= time() ?>.less"/>
    <script src="/js/less-1.2.1.js" type="text/javascript"></script>

    <?php
        /* @var $asset Asset */
        $asset = build('asset');
        $asset->load_js('admin.js');
    ?>
    <?= $asset->scripts() ?>
</head>

<body id="admin_page">

    <?= $content ?>


    <?= js() ?>
</body>
</html>
