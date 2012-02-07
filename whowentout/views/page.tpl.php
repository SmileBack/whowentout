<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">
    <meta name="viewport" content="width=320px, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.0000000001.less"/>
    <link rel="stylesheet/less" type="text/css" href="/css/dialog.0000000003.less"/>
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

    <link rel="stylesheet" type="text/css" href="/css/jquery.datatables.css"/>
    <script type="text/javascript" src="/js/jquery.datatables.js"></script>

</head>

<body class="<?= browser::classes() ?>">

<nav id="nav">

    <a class="logo" href="/"><img src="/images/logo.transparent.png"/></a>

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


    <?php if (browser::is_mobile()): ?>
    <div id="footer">
        <?= auth()->get_login_link(); ?>
    </div>
    <?php endif; ?>

</div>
<!-- page end -->

<?= flash::message() ?>

<?= js() ?>

    <?=
    r::debug_panel(array(
        'body' => r::table(array(
            'rows' => benchmark::summary('database'),
        ))
    ))
    ?>

</body>
</html>
